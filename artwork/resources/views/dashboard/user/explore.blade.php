<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Explore Artworks</h1>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($artworks as $artwork)
            <div class="relative group rounded-lg overflow-hidden shadow hover:shadow-lg bg-white">
                <img src="{{ asset($artwork->file_path) }}" alt="{{ $artwork->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center">
                    <span class="text-white text-lg font-semibold opacity-0 group-hover:opacity-100">{{ $artwork->title }}</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset($artwork->user->profile_picture ?? 'default.png') }}" class="w-7 h-7 rounded-full border" alt="{{ $artwork->user->name }}">
                        <span class="text-sm font-medium">{{ $artwork->user->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-pink-500"><i class="fas fa-heart"></i></span>
                        <span class="text-sm">{{ $artwork->likes->count() }}</span>
                        <span class="text-yellow-500"><i class="fas fa-star"></i></span>
                        <span class="text-sm">{{ $artwork->favorites->count() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
