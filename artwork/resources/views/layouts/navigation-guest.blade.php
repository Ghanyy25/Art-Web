<nav class="w-64 h-screen bg-white shadow-lg flex flex-col flex-shrink-0">

    {{-- 1. Header (Logo) --}}
    <div class="flex items-center justify-center h-16 border-b">
        <a href="{{ route('explore') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    {{-- 2. Menu Links (Tengah) --}}
    <div class="flex-1 py-6 space-y-2 overflow-y-auto">

        {{-- Link Explore --}}
        <x-nav-link-vertical :href="route('explore')" :active="request()->routeIs('explore')">
            {{ __('Explore') }}
        </x-nav-link-vertical>

        {{-- Link Challenges (Publik) --}}
        <x-nav-link-vertical :href="route('challenges.index')" :active="request()->routeIs('challenges.index')">
            {{ __('Challenges') }}
        </x-nav-link-vertical>

    </div>

    {{-- 3. Footer (Login & Register) --}}
    <div class="p-4 border-t bg-gray-50 space-y-3">

        {{-- Tombol Login --}}
        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Log in
        </a>

        {{-- Tombol Register --}}
        <a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Register
        </a>

    </div>

</nav>
