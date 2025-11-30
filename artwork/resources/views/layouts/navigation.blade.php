<nav class="w-64 h-screen bg-white shadow-lg flex flex-col flex-shrink-0 " x-data="{ open: false }">

    <div class="flex items-center justify-center h-16 border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="flex-1 py-6 space-y-2 overflow-y-auto border">

        @if(Auth::user()->role == 'admin')

            <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Admin Dashboard') }}
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index')">
                Manajemen Kategori
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                Manajemen User
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('admin.moderation.index')" :active="request()->routeIs('admin.moderation.index')">
                Moderasi
            </x-nav-link-vertical>

        @elseif(Auth::user()->role == 'curator')

            <x-nav-link-vertical :href="route('curator.challenges.index')" :active="request()->routeIs('curator.challenges.index')">
                Manajemen Challenge
            </x-nav-link-vertical>


        @else

            <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Home') }}
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('explore')" :active="request()->routeIs('explore')">
                {{ __('Explore') }}
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('challenges.index')" :active="request()->routeIs('challenges.index')">
            {{ __('Challenges') }}
        </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('artworks.index')" :active="request()->routeIs('artworks.index')">
                My Artworks
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
                My Favorites
            </x-nav-link-vertical>

        @endif
        </div>

    <div class="p-4 border-t flex items-center justify-between border" x-data="{ confirmLogout: false }">
        {{-- 1. Link Profil (Kiri) --}}
        @php
            $profileRoute = '#';
            if(Auth::user()->role == 'curator') {
                // Jika Curator, arahkan ke Profil Publik Kurator
                $profileRoute = route('curator.profile.public', Auth::id());
            } elseif(Auth::user()->role == 'member') {
                // Jika Member biasa, arahkan ke Profil Creator
                $profileRoute = route('profile.show', Auth::id());
            }
        @endphp

    {{-- 1. Link Profil (Kiri) --}}
        <a href="{{ $profileRoute }}"
        class="flex items-center flex-1 min-w-0 p-2 mr-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-800 hover:bg-gray-50 transition ease-in-out duration-150">

            <img class="h-8 w-8 rounded-full object-cover mr-2 flex-shrink-0"
                src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                alt="{{ Auth::user()->name }}">

            <div class="truncate">{{ Auth::user()->name }}</div>
        </a>

        {{-- 2. Tombol Trigger Logout (Kanan) --}}
        <button @click="confirmLogout = true"
                class="p-2 text-gray-500 bg-white border border-gray-200 rounded-full shadow-sm hover:bg-red-50 hover:text-red-600 hover:border-red-200 focus:outline-none transition"
                title="Keluar">
            {{-- Icon Pintu Keluar --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </button>

        {{-- 3. Modal Pop-up Konfirmasi --}}
        <div x-show="confirmLogout" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            {{-- Backdrop (Latar Gelap) --}}
            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="confirmLogout = false"></div>

            {{-- Konten Modal --}}
            <div class="relative bg-white rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-md overflow-hidden">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        {{-- Icon Peringatan --}}
                        <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Konfirmasi Keluar
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Apakah Anda yakin ingin keluar? Anda harus login kembali untuk mengakses akun Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    {{-- Tombol Batal --}}
                    <button @click="confirmLogout = false"
                            type="button"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                        Batal
                    </button>

                    {{-- Tombol Ya, Keluar --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Ya, Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</nav>
