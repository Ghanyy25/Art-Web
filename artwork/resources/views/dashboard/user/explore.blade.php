<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-center w-full py-2">
            <form method="GET" action="{{ route('explore') }}" class="w-full max-w-3xl">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="relative w-full">
                    <input
                    type="text"
                    name="search"
                    class="block w-full pl-12 pr-4 py-3 bg-gray-100 border-none rounded-full text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white shadow-inner sm:text-sm transition-colors"
                    placeholder="Cari inspirasi, judul karya, atau nama kreator..."
                    value="{{ request('search') }}"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-3">

            {{-- Filter Kategori Dropdown --}}
            <div class="flex justify-between items-center mb-6 relative z-30">
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button
                        @click="open = !open"
                        @click.outside="open = false"
                        type="button"
                        class="inline-flex justify-between items-center w-56 px-4 py-2 bg-white border border-gray-300 rounded-full shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                    >
                        <span>
                            {{ $categories->firstWhere('slug', request('category'))->name ?? 'Semua Kategori' }}
                        </span>
                        <svg class="-mr-1 ml-2 h-5 w-5 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-left absolute left-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden z-50"
                        style="display: none;"
                    >
                        <div class="py-1">
                            <a href="{{ route('explore', ['search' => request('search')]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ !request('category') ? 'bg-indigo-50 font-semibold text-indigo-700' : '' }}">
                                Semua Kategori
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('explore', ['category' => $category->slug, 'search' => request('search')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request('category') == $category->slug ? 'bg-indigo-50 font-semibold text-indigo-700' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Cek jika artwork kosong --}}
            @if($artworks->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada karya ditemukan</h3>
                    <p class="text-gray-500 mt-1">Coba kata kunci lain atau reset filter Anda.</p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('explore') }}" class="mt-4 px-6 py-2 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition">
                            Reset Filter
                        </a>
                    @endif
                </div>
            @else
                {{-- LAYOUT MASONRY (Style Sama dengan Favorites) --}}
                {{-- Note: space-y-4 penting untuk jarak vertikal di masonry --}}
                <div class="columns-2 sm:columns-3 md:columns-5 lg:columns-5 gap-4 space-y-4">

                    @foreach($artworks as $artwork)
                        <div class="break-inside-avoid relative group rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200">

                            <a href="{{ route('artworks.show', $artwork->id) }}" class="block relative">
                                {{-- Gambar --}}
                                <img src="{{ Storage::url($artwork->file_path) }}"
                                     alt="{{ $artwork->title }}"
                                     class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">

                                {{-- Overlay Gradient --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                {{-- Info di Overlay (Judul & User) --}}
                                <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <h4 class="text-white font-bold truncate text-shadow-sm">{{ $artwork->title }}</h4>

                                    <div class="flex items-center gap-2 mt-2">
                                        <img src="{{ $artwork->user->profile_picture ? Storage::url($artwork->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) . '&background=random&size=128' }}"
                                             class="w-6 h-6 rounded-full border border-white/50 object-cover">
                                        <span class="text-xs text-gray-200 truncate hover:text-white transition">{{ $artwork->user->name }}</span>
                                    </div>
                                </div>
                            </a>

                        </div>
                    @endforeach

                </div>

                <div class="mt-10 mb-20">
                    {{ $artworks->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
