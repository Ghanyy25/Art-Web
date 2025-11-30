<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Favorites
            </h2>
            <span class="text-sm text-gray-500">{{ $favorites->count() }} karya</span>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        @if($favorites->isEmpty())
            <div class="text-center py-20">
                <i class="fas fa-heart-broken text-7xl text-gray-200 mb-6"></i>
                <h3 class="text-2xl font-medium text-gray-600">Belum ada karya favorit</h3>
                <p class="text-gray-500 mt-3">Jelajahi karya menarik dan tekan ikon hati untuk menyimpannya di sini</p>
            </div>
        @else
            <!-- PINTEREST MASONRY GRID -->
            <div class="columns-2 sm:columns-3 md:columns-4 lg:columns-5 xl:columns-6 gap-5 space-y-5">

                @foreach($favorites as $favorite)
                    @php $artwork = $favorite->artwork; @endphp

                    <div class="break-inside-avoid group relative rounded-2xl overflow-hidden bg-white shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3">

                        <a href="{{ route('artworks.show', $artwork) }}" class="block">
                            <div class="relative overflow-hidden">
                                <img src="{{ Storage::url($artwork->file_path) }}"
                                     alt="{{ $artwork->title }}"
                                     class="w-full object-cover rounded-2xl group-hover:scale-110 transition-transform duration-700">

                                <!-- Overlay gelap + info muncul dari bawah saat hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                <div class="absolute bottom-0 left-0 right-0 p-6 text-white transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                                    <h3 class="font-bold text-lg line-clamp-2 mb-3">{{ $artwork->title }}</h3>

                                    <div class="flex items-center justify-between">
                                        <!-- Artist -->
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $artwork->user->profile_picture
                                                ? Storage::url($artwork->user->profile_picture)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) . '&background=random&color=fff&size=80'
                                                 }}"
                                                 alt="{{ $artwork->user->name }}"
                                                 class="w-10 h-10 rounded-full ring-2 ring-white object-cover">

                                            <div>
                                                <p class="font-semibold">{{ $artwork->user->name }}</p>
                                                <p class="text-xs opacity-80">Kreator</p>
                                            </div>
                                        </div>

                                        <!-- Stats -->
                                        <div class="flex gap-5 text-sm">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-heart text-pink-400"></i>
                                                {{ $artwork->likes->count() }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-star text-yellow-400"></i>
                                                {{ $artwork->favorites->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ikon hati kecil di pojok kiri atas (opsional, biar estetik) -->
                                <div class="absolute top-3 left-3 bg-red-500 text-white p-2 rounded-full opacity-80">
                                    <i class="fas fa-heart text-sm"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
</x-app-layout>
