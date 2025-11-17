<nav class="w-64 h-screen bg-white shadow-lg flex flex-col flex-shrink-0" x-data="{ open: false }">

    <div class="flex items-center justify-center h-16 border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="flex-1 py-6 space-y-2 overflow-y-auto">

        @if(Auth::user()->role == 'admin')

            <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Admin Dashboard') }}
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index')">
                Manajemen Kategori
            </x-nav-link-vertical>
            {{-- <x-nav-link-vertical :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                Manajemen User
            </x-nav-link-vertical> --}}

        @elseif(Auth::user()->role == 'curator')

            <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Curator Dashboard') }}
            </x-nav-link-vertical>
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
            <x-nav-link-vertical :href="route('artworks.index')" :active="request()->routeIs('artworks.index')">
                My Artworks
            </x-nav-link-vertical>
            <x-nav-link-vertical :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
                My Favorites
            </x-nav-link-vertical>

        @endif
        </div>

    <div class="p-4 border-t">
        <a href="{{ route('profile.show', ['id' => Auth::id()]) }}"
           class="flex items-center w-full p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-800 focus:outline-none transition ease-in-out duration-150">

            <img class="h-8 w-8 rounded-full object-cover mr-2"
                 src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                 alt="{{ Auth::user()->name }}">

            <div class="flex-1 text-left">{{ Auth::user()->name }}</div>
        </a>
    </div>
</nav>
