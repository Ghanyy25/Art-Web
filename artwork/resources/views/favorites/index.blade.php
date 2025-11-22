<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Favorites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($favorites as $favorite)
                <a href="{{ route('artworks.show', $favorite->artwork) }}">
                <div class="relative group rounded-lg overflow-hidden shadow hover:shadow-lg bg-white">
                    <img src="{{ Storage::url($favorite->artwork->file_path) }}" alt="{{ $favorite->artwork->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center">
                        <span class="text-white text-lg font-semibold opacity-0 group-hover:opacity-100">{{$favorite->artwork->title }}</span>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2">
                        <div class="flex items-center gap-2">

                            <img src="{{ $favorite->artwork->user->profile_picture ? Storage::url($favorite->artwork->user->profile_picture) : asset('images/Default.png') }}"
                            alt="{{ $favorite->artwork->user->name }}"
                            class="h-8 w-8 rounded-full object-cover">

                            <span class="text-sm font-medium">{{ $favorite->artwork->user->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-pink-500"><i class="fas fa-heart"></i></span>
                            <span class="text-sm">{{ $favorite->artwork->likes->count() }}</span>
                            <span class="text-yellow-500"><i class="fas fa-star"></i></span>
                            <span class="text-sm">{{ $favorite->artwork->favorites->count() }}</span>
                        </div>
                    </div>
                </div>
                </a>
                @endforeach
            </div>
            </div>
    </div>
</x-app-layout>
