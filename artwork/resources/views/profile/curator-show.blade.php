<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Kurator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- BAGIAN 1: INFO KURATOR --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-8">

                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-indigo-100 shadow-lg"
                             src="{{ $curator->profile_picture ? Storage::url($curator->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($curator->name) . '&background=random' }}"
                             alt="{{ $curator->name }}">
                    </div>

                    {{-- Info Teks --}}
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row items-center gap-3 mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $curator->name }}</h1>
                            <span class="px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full uppercase tracking-wide">
                                Verified Curator
                            </span>
                        </div>

                        <p class="text-gray-600 mb-6 max-w-2xl">
                            {{ $curator->bio ?? 'Kurator ini belum menuliskan bio.' }}
                        </p>

                        {{-- Cek: Apakah user yang login ADALAH pemilik profil ini? --}}
                        @if(auth()->check() && auth()->id() === $curator->id)
                            <div class="mt-4 flex space-x-3">
                                {{-- Tombol Edit Profil --}}
                                <a href="{{ route('profile.edit') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit Profil
                                </a>

                                {{-- Tombol Ke Dashboard --}}
                                <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    Dashboard
                                </a>
                            </div>
                        @endif

                        {{-- Statistik --}}
                        <div class="flex justify-center md:justify-start gap-8 border-t pt-6 w-full md:w-auto">
                            <div class="text-center">
                                <span class="block text-2xl font-bold text-gray-900">{{ $totalChallenges }}</span>
                                <span class="text-sm text-gray-500">Total Challenge</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-2xl font-bold text-gray-900">{{ $activeChallenges }}</span>
                                <span class="text-sm text-green-600 font-medium">Sedang Aktif</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-2xl font-bold text-gray-900">{{ $curator->created_at->format('Y') }}</span>
                                <span class="text-sm text-gray-500">Bergabung Sejak</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: DAFTAR CHALLENGE --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Challenge by {{ $curator->name }}
                </h3>

                @if($challenges->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($challenges as $challenge)
                            @php
                                $now = now();
                                $status = ''; $statusClass = '';
                                if ($now < $challenge->start_date) {
                                    $status = 'Akan Datang'; $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($now >= $challenge->start_date && $now <= $challenge->end_date) {
                                    $status = 'Sedang Berjalan'; $statusClass = 'bg-green-100 text-green-800';
                                } else {
                                    $status = 'Selesai'; $statusClass = 'bg-gray-100 text-gray-800';
                                }
                            @endphp

                            <a href="{{ route('challenges.show', $challenge->slug ?? $challenge->id) }}" class="block group">
                                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 transform group-hover:-translate-y-1">
                                    {{-- Banner --}}
                                    <div class="relative h-48 bg-gray-200">
                                        <img src="{{  Storage::url($challenge->banner_image) ?  Storage::url($challenge->banner_image) : asset('images/default_banner.jpg') }}"
                                             alt="{{ $challenge->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-6">
                                        <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                            {{ $challenge->title }}
                                        </h4>
                                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                                            {{ $challenge->description }}
                                        </p>

                                        <div class="flex items-center text-xs text-gray-400 border-t pt-4">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg p-8 text-center shadow-sm border border-gray-100">
                        <p class="text-gray-500">Kurator ini belum memiliki challenge publik saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
