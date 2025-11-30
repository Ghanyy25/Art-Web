<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Favorites') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6">

            @if($favorites->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <i class="fas fa-heart-broken text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada favorit</h3>
                    <p class="text-gray-500 mt-1 max-w-sm">Jelajahi karya seni dan simpan yang Anda suka di sini.</p>
                    <a href="{{ route('explore.index') }}" class="mt-6 px-6 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
                        Mulai Menjelajah
                    </a>
                </div>
            @else
                {{-- MASONRY GRID LAYOUT (Seperti Explore) --}}
                <div class="columns-2 md:columns-3 lg:columns-5 gap-4 space-y-4">
                    @foreach($favorites as $favorite)
                        <div class="break-inside-avoid relative group rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">

                            {{-- Link ke Detail Artwork --}}
                            <a href="{{ route('artworks.show', $favorite->artwork->id) }}" class="block relative">
                                {{-- Gambar (Width Full, Height Auto agar masonry bekerja) --}}
                                <img src="{{ Storage::url($favorite->artwork->file_path) }}"
                                     alt="{{ $favorite->artwork->title }}"
                                     class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">

                                {{-- Overlay Gelap saat Hover --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                {{-- Info di Overlay (Judul & User) --}}
                                <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <h4 class="text-white font-bold truncate text-shadow-sm">{{ $favorite->artwork->title }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <img src="{{ $favorite->artwork->user->profile_picture ? Storage::url($favorite->artwork->user->profile_picture) : asset('images/default.png') }}"
                                             class="w-5 h-5 rounded-full border border-white/50 object-cover">
                                        <span class="text-xs text-gray-200 truncate">{{ $favorite->artwork->user->name }}</span>
                                    </div>
                                </div>
                            </a>

                            {{-- Tombol Action Cepat (Opsional: Unfavorite langsung dari sini) --}}


                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $favorites->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Script AJAX Simple untuk Unfavorite tanpa reload (Opsional) --}}
    @push('scripts')
    <script>
        function toggleFavorite(btn, artworkId) {
            if(!confirm('Hapus dari favorit?')) return;

            fetch(`/favorites/${artworkId}`, { // Pastikan route ini sesuai dengan route toggle Anda
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Hilangkan item dari grid dengan animasi
                    const card = btn.closest('.break-inside-avoid');
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();
                        // Reload jika kosong agar layout rapi (opsional)
                    }, 300);
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
