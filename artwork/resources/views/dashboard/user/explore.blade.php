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

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-0">

            {{-- Filter Kategori Dropdown --}}
            <div class="flex justify-start mb-5 px-1 sm:px-0 relative z-30" x-data="{ open: false }">

                <div class="relative inline-block text-left">
                    <button
                        @click="open = !open"
                        @click.outside="open = false"
                        type="button"
                        class="inline-flex justify-between items-center w-56 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        id="menu-button"
                        aria-expanded="true"
                        aria-haspopup="true"
                    >
                        <span>
                            {{ $categories->firstWhere('slug', request('category'))->name ?? 'Semua Kategori' }}
                        </span>

                        <svg class="-mr-1 ml-2 h-5 w-5 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                        class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="menu-button"
                        tabindex="-1"
                        style="display: none;"
                    >
                        <div class="py-1" role="none">
                            <a href="{{ route('explore', ['search' => request('search')]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ !request('category') ? 'bg-gray-100 font-semibold' : '' }}"
                                role="menuitem">
                                Semua Kategori
                            </a>

                            @foreach($categories as $category)
                                <a href="{{ route('explore', ['category' => $category->slug, 'search' => request('search')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request('category') == $category->slug ? 'bg-gray-100 font-semibold' : '' }}"
                                    role="menuitem">
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
                    <div class="bg-gray-100 rounded-full p-6 mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada karya ditemukan</h3>
                    <p class="text-gray-500 mt-1">Coba cari dengan kata kunci lain atau ubah kategori.</p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('explore') }}" class="mt-4 text-indigo-600 hover:underline font-medium">
                            Hapus semua filter
                        </a>
                    @endif
                </div>
            @else

                {{-- LAYOUT MASONRY (Pinterest Style) --}}
                {{-- Menggunakan 'columns' alih-alih 'grid' --}}
                <div class="columns-2 sm:columns-3 md:columns-4 lg:columns-5 gap-4 px-2 sm:px-0 ">

                    @foreach($artworks as $artwork)
                    {{-- 'break-inside-avoid' mencegah item terpotong antar kolom --}}
                    {{-- 'mb-4' memberikan jarak vertikal antar item --}}
                    <div class="break-inside-avoid mb-4 relative group rounded-xl overflow-hidden shadow-sm hover:shadow-md bg-white transition-shadow duration-300 border border-gray-200">

                        {{-- Hapus 'min-h-[150px]' dan biarkan 'h-auto' agar tinggi mengikuti gambar --}}
                        <img src="{{ Storage::url($artwork->file_path) }}" alt="{{ $artwork->title }}" class="w-full h-auto object-cover block">

                        <a href="{{ route('artworks.show', $artwork->id) }}" class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></a>

                        <div class="p-3 bg-white">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $artwork->title }}</h3>
                            <div class="flex items-center justify-between mt-1">
                                <a href="{{ route('profile.show', $artwork->user->id) }}" class="flex items-center gap-2 group/user">
                                    <img src="{{
                                                $artwork->user->profile_picture
                                                    ? Storage::url($artwork->user->profile_picture)
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) . '&background=random&size=128'
                                            }}"
                                         class="w-5 h-5 rounded-full object-cover"
                                         alt="{{ $artwork->user->name }}">
                                    <span class="text-xs text-gray-500 group-hover/user:underline truncate max-w-[80px]">{{ $artwork->user->name }}</span>
                                </a>
                                <div class="flex items-center text-xs text-gray-400">
                                    <i class="fas fa-heart mr-1 {{ $artwork->likes_count > 0 ? 'text-pink-500' : '' }}"></i>
                                    {{ $artwork->likes->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="mt-10">
                    {{ $artworks->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
