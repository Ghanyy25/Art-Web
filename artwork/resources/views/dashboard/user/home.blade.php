<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI: FEED KARYA (Utama) --}}
                <div class="w-full lg:w-2/3">

                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        Terbaru dari yang Anda Ikuti
                    </h3>

                    @if($feedArtworks->count() > 0)
                        <div class="space-y-8">
                            @foreach($feedArtworks as $artwork)
                                {{-- Card Feed Item --}}
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                                    {{-- Header Card: Info User --}}
                                    <div class="p-4 flex items-center justify-between border-b border-gray-50 bg-gray-50/50">
                                        <div class="flex items-center">
                                            <a href="{{ route('profile.show', $artwork->user->id) }}">
                                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                                     src="{{ $artwork->user->profile_picture ? Storage::url($artwork->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) }}"
                                                     alt="{{ $artwork->user->name }}">
                                            </a>
                                            <div class="ml-3">
                                                <a href="{{ route('profile.show', $artwork->user->id) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 hover:underline">
                                                    {{ $artwork->user->name }}
                                                </a>
                                                <span class="block text-xs text-gray-500">{{ $artwork->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        {{-- Tombol Detail Kecil --}}
                                        <a href="{{ route('artworks.show', $artwork->id) }}" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                        </a>
                                    </div>

                                    {{-- Gambar Artwork (Link ke Detail) --}}
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="block bg-gray-100">
                                        <img src="{{ Storage::url($artwork->file_path) }}"
                                             alt="{{ $artwork->title }}"
                                             class="w-full h-auto max-h-[500px] object-contain mx-auto">
                                    </a>

                                    {{-- Footer Card: Judul & Deskripsi --}}
                                    <div class="p-5">
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="hover:text-indigo-600">{{ $artwork->title }}</a>
                                        </h4>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                                            {{ $artwork->description }}
                                        </p>

                                        {{-- Statistik Singkat (Opsional) --}}
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                {{ $artwork->likes->count() }} Suka
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                {{ $artwork->comments->count() }} Komentar
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $feedArtworks->links() }}
                        </div>

                    @else
                        {{-- Empty State (Jika belum follow siapapun / belum ada post) --}}
                        <div class="bg-white rounded-lg shadow-sm p-8 text-center border border-gray-200">
                            <div class="bg-indigo-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Feed Anda Masih Kosong</h3>
                            <p class="text-gray-500 mt-2 mb-6 max-w-md mx-auto">
                                Ikuti kreator dan kurator favorit Anda untuk melihat karya terbaru mereka muncul di sini.
                            </p>
                            <a href="{{ route('explore') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150">
                                Jelajahi Karya (Explore)
                            </a>
                        </div>
                    @endif
                </div>

                {{-- KOLOM KANAN: SIDEBAR (Challenge & Rekomendasi) --}}
                <div class="w-full lg:w-1/3 space-y-6">

                    {{-- Widget Challenge Aktif --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900">Challenge Aktif</h3>
                            <a href="{{ route('challenges.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua</a>
                        </div>

                        @if(isset($activeChallenges) && $activeChallenges->count() > 0)
                            <div class="space-y-4">
                                @foreach($activeChallenges as $challenge)
                                    <a href="{{ route('challenges.show', $challenge->slug ?? $challenge->id) }}" class="block group">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md overflow-hidden">
                                                <img src="{{ $challenge->banner_image ? Storage::url($challenge->banner_image) : asset('images/default_banner.jpg') }}" class="w-full h-full object-cover group-hover:opacity-80 transition">
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 line-clamp-1">
                                                    {{ $challenge->title }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Berakhir: {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada challenge aktif saat ini.</p>
                        @endif
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
