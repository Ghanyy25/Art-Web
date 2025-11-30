<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">

            <!-- Judul Kiri -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil Kreator') }}
            </h2>

            <!-- Ikon Setting + Dropdown (pakai Alpine.js biar ringan) -->
        @if(auth()->check() && auth()->id() === $creator->id)
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open"
                        class="p-2 rounded-full hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <i class="fas fa-cog text-xl text-gray-600"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                    x-transition
                    class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden z-50">
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <i class="fas fa-user-cog mr-3 w-5"></i>
                            Kelola Akun
                        </a>

                        <hr class="border-gray-100">

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt mr-3 w-5"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        </div>
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

                        <div class="flex items-center gap-6 mt-4 text-center md:text-left">

                        {{-- Bagian Followers (Pengikut) --}}
                        <div>
                            <span id="follower-count-{{ $creator->id }}" class="block text-2xl font-bold text-gray-900">
                                {{ $creator->followers()->count() }}
                            </span>
                            <span class="text-sm text-gray-500">Pengikut</span>
                        </div>

                        {{-- Bagian Following (Mengikuti) --}}
                        <div>
                            <span class="block text-2xl font-bold text-gray-900">
                                {{ $creator->following()->count() }}
                            </span>
                            <span class="text-sm text-gray-500">Mengikuti</span>
                        </div>

                        {{-- Tombol Follow/Unfollow (Hanya muncul jika user yang login BUKAN pemilik profil) --}}
                        @if(auth()->check() && auth()->id() !== $creator->id)
                        <button
                            onclick="toggleFollow({{ $creator->id }})"
                            id="follow-btn-{{ $creator->id }}"
                            class="ml-2 px-4 py-1.5 rounded-full text-sm font-semibold transition-all border {{ auth()->user()->isFollowing($creator->id) ? 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-indigo-600 text-white border-transparent hover:bg-indigo-700 shadow-sm' }}">
                            {{ auth()->user()->isFollowing($creator->id) ? 'Mengikuti' : 'Ikuti' }}
                        </button>
                    @endif

                    </div>
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
                                <a href="{{ route('artworks.show', $artwork->id) }}" class="group relative block aspect-square overflow-hidden rounded-lg bg-gray-100 border">
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
    <script>
    function toggleFollow(userId) {
        axios.post('/user/' + userId + '/follow')
            .then(function (response) {
                const data = response.data;
                const btn = document.getElementById('follow-btn-' + userId);
                const countSpan = document.getElementById('follower-count-' + userId);

                // Update Angka
                if(countSpan) countSpan.innerText = data.followers_count;

                // Update Tampilan Tombol
                if (data.is_following) {
                    // Jadi State "Mengikuti"
                    btn.innerText = "Mengikuti";
                    btn.classList.remove('bg-indigo-600', 'text-white', 'border-transparent', 'hover:bg-indigo-700');
                    btn.classList.add('bg-gray-100', 'text-gray-800', 'border-gray-300', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
                } else {
                    // Jadi State "Ikuti"
                    btn.innerText = "Ikuti";
                    btn.classList.remove('bg-gray-100', 'text-gray-800', 'border-gray-300', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
                    btn.classList.add('bg-indigo-600', 'text-white', 'border-transparent', 'hover:bg-indigo-700');
                }
            })
            .catch(function (error) {
                if(error.response && error.response.status === 401) {
                    alert('Silakan login untuk mengikuti kurator ini.');
                    window.location.href = '/login';
                } else {
                    console.error(error);
                }
            });
    }

    // Efek Hover khusus untuk tombol "Mengikuti" agar berubah jadi "Unfollow" (Opsional UX)
    document.addEventListener('DOMContentLoaded', () => {
        const followBtns = document.querySelectorAll('[id^="follow-btn-"]');
        followBtns.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                if(btn.innerText === 'Mengikuti') {
                    btn.innerText = 'Batal Ikuti';
                }
            });
            btn.addEventListener('mouseleave', () => {
                if(btn.innerText === 'Batal Ikuti') {
                    btn.innerText = 'Mengikuti';
                }
            });
        });
    });
</script>
</x-app-layout>
