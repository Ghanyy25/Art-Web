<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Tampilkan nama kreator di header --}}
            {{ $creator->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 md:flex">
                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6 text-center">
                        <img class="h-32 w-32 rounded-full object-cover mx-auto"
                             src="{{ $creator->profile_picture ? Storage::url($creator->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($creator->name) . '&size=128' }}"
                             alt="{{ $creator->name }}">
                    </div>

                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $creator->name }}</h1>

                            @auth
                                @if(Auth::id() == $creator->id)
                                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-200 text-sm font-medium text-gray-800 rounded-md hover:bg-gray-300">
                                        Edit Profile
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <p class="text-gray-600 mb-4">{{ $creator->bio }}</p>

                        @if($creator->external_links)
                            <div class="flex space-x-4">
                                @foreach($creator->external_links as $label => $link)
                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        {{ ucfirst($label) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Gallery</h3>

                    @if($artworks->isEmpty())
                        <p class="text-gray-500">Kreator ini belum mengunggah karya apapun.</p>
                    @else
                        <div class="grid grid-cols-3 gap-1 md:gap-4">
                            @foreach($artworks as $artwork)
                                <a href="{{ route('artworks.show', $artwork->id) }}" class="relative aspect-square group">
                                    <img src="{{ Storage::url($artwork->file_path) }}"
                                         alt="{{ $artwork->title }}"
                                         class="w-full h-full object-cover rounded-md group-hover:opacity-75 transition-opacity">

                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold opacity-0 group-hover:opacity-100">{{ $artwork->title }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $artworks->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
