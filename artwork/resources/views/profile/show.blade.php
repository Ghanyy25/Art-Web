<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Kreator') }}
        </h2>
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BAGIAN 1: INFO PROFIL KREATOR --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col md:flex-row md:items-start gap-6">

                    {{-- Foto Profil --}}
                    <div class="flex-shrink-0 mx-auto md:mx-0">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-gray-100 shadow-sm"
                             src="{{ $creator->profile_picture ? asset('storage/' . $creator->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($creator->name) . '&background=random&size=128' }}"
                             alt="{{ $creator->name }}">
                    </div>

                    {{-- Detail Info --}}
                    <div class="flex-grow text-center md:text-left space-y-3">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $creator->name }}</h1>
                                <p class="text-sm text-gray-500">Member sejak {{ $creator->created_at->format('d M Y') }}</p>
                            </div>

                            {{-- Tombol Edit (Hanya muncul jika user yang login adalah pemilik profil) --}}
                            @auth
                                @if(Auth::id() === $creator->id)
                                    <div class="mt-4 md:mt-0">
                                        <a href="{{ route('profile.editprofile') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            {{ __('Edit Profil') }}
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        {{-- Bio --}}
                        <div class="text-gray-700 leading-relaxed">
                            @if($creator->bio)
                                <p>{{ $creator->bio }}</p>
                            @else
                                <span class="text-gray-400 italic">{{ __('Belum ada bio.') }}</span>
                            @endif
                        </div>

                        {{-- Social Links --}}
                        @if(!empty($creator->external_links))
                            <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                                @foreach($creator->external_links as $platform => $link)
                                    @if($link)
                                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                            {{-- Ikon Kecil (Opsional generic icon) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            {{ ucfirst($platform) }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: GALERI KARYA --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Galeri Portofolio') }}</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            {{ $artworks->total() }} Karya
                        </span>
                    </div>

                    @if($artworks->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">{{ __('Belum ada karya yang diunggah.') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($artworks as $artwork)
                                <a href="{{ route('artworks.show', $artwork->id) }}" class="group relative block aspect-square overflow-hidden rounded-lg bg-gray-100">
                                    {{-- Gambar --}}
                                    <img src="{{ asset('storage/' . $artwork->file_path) }}"
                                         alt="{{ $artwork->title }}"
                                         class="h-full w-full object-cover object-center transition duration-300 group-hover:scale-110">

                                    {{-- Overlay Hover --}}
                                    <div class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100 flex items-center justify-center p-4 text-center">
                                        <div>
                                            <h4 class="text-white font-semibold text-sm truncate">{{ $artwork->title }}</h4>
                                            <p class="text-gray-200 text-xs mt-1">Lihat Detail</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $artworks->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
