<x-app-layout>
    {{-- 1. SEARCH BAR DI HEADER (Gaya Explore) --}}
    <x-slot name="header">
        <div class="flex justify-center w-full py-0">
            <form method="GET" action="{{ route('challenges.index') }}" class="w-full max-w-3xl">
                {{-- Simpan filter saat ini agar tidak hilang saat mencari --}}
                <input type="hidden" name="filter" value="{{ request('filter', 'active') }}">

                <div class="relative w-full">
                    <input
                        type="text"
                        name="search"
                        class="block w-full pl-12 pr-4 py-3 bg-gray-100 border-none rounded-full text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white shadow-inner sm:text-sm transition-colors"
                        placeholder="Cari challenge, kompetisi, atau topik..."
                        value="{{ request('search') }}"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-0">

            {{-- 2. FILTER TOMBOL (Di Bagian Atas Konten) --}}
            <div class="flex flex-col items-center mb-5">
                <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-200 inline-flex">
                    {{-- Tombol SEMUA --}}
                    <a href="{{ route('challenges.index', ['filter' => 'all', 'search' => request('search')]) }}"
                        class="px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'all' ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        Semua
                    </a>

                    {{-- Tombol BERLANGSUNG --}}
                    <a href="{{ route('challenges.index', ['filter' => 'active', 'search' => request('search')]) }}"
                        class="px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'active' ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        Sedang Berlangsung
                    </a>

                    {{-- Tombol AKAN DATANG --}}
                    <a href="{{ route('challenges.index', ['filter' => 'upcoming', 'search' => request('search')]) }}"
                        class="px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'upcoming' ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        Akan Datang
                    </a>

                    {{-- Tombol SELESAI --}}
                    <a href="{{ route('challenges.index', ['filter' => 'ended', 'search' => request('search')]) }}"
                        class="px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'ended' ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        Sudah Selesai
                    </a>
                </div>
            </div>

            {{-- 3. DAFTAR CHALLENGE --}}
            @if($challenges->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border border-dashed border-gray-300 text-center mx-4 sm:mx-0">
                    <div class="bg-gray-50 p-4 rounded-full mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900">Tidak ada challenge ditemukan</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">
                        Coba ubah kata kunci pencarian atau ganti filter status challenge Anda.
                    </p>
                    @if(request('search') || $filter !== 'active')
                        <a href="{{ route('challenges.index') }}" class="mt-6 text-indigo-600 hover:underline font-medium">
                            Reset Semua
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-0 sm:px-0">
                    @foreach($challenges as $challenge)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 flex flex-col h-full group">

                            {{-- Gambar Banner --}}
                            <div class="h-48 w-full bg-gray-200 relative overflow-hidden">
                                @if($challenge->banner_image)
                                    <img src="{{ Storage::url($challenge->banner_image) }}" alt="{{ $challenge->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                                        <i class="fas fa-image text-4xl opacity-50"></i>
                                    </div>
                                @endif

                                {{-- Badge Status --}}
                                <div class="absolute top-3 right-3">
                                    @if($challenge->end_date> now() and $challenge->start_date <= now())
                                        <span class="px-3 py-1 text-xs font-bold text-white bg-green-500/90 backdrop-blur-sm rounded-full shadow-sm">
                                            Berlangsung
                                        </span>
                                    @elseif($challenge->start_date > now())
                                        <span class="px-3 py-1 text-xs font-bold text-white bg-blue-600/90 backdrop-blur-sm rounded-full shadow-sm">
                                            Akan Datang
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold text-white bg-gray-600/90 backdrop-blur-sm rounded-full shadow-sm">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold text-gray-900 line-clamp-1 mb-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $challenge->title }}
                                </h3>

                                <p class="text-sm text-gray-600 line-clamp-2 mb-6 flex-grow leading-relaxed">
                                    {{ $challenge->description }}
                                </p>

                                <div class="border-t border-gray-100 pt-4 mt-auto">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                        <div class="flex items-center" title="Batas Waktu">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $challenge->end_date->format('d M Y h:m') }}
                                        </div>
                                        
                                    </div>

                                    @if($challenge->start_date > now())
        {{-- KONDISI 1: AKAN DATANG (Belum Mulai) --}}
        <button type="button" disabled class="block w-full text-center px-4 py-2.5 bg-gray-300 border border-transparent rounded-lg font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
            <i class="fas fa-lock mr-1"></i> Belum Dibuka
        </button>
    @else
        {{-- KONDISI 2: SEDANG BERLANGSUNG / SELESAI --}}
        <a href="{{ route('challenges.show', $challenge->slug) }}" class="block w-full text-center px-4 py-2.5 bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 focus:bg-indigo-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Lihat Detail
        </a>
    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $challenges->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
