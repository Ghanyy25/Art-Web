<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Challenge') }}
            </h2>
            <a href="{{ route('curator.challenges.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- BAGIAN 1: BANNER & HEADER --}}
                <div class="relative h-64 bg-gray-200">
                    @if($challenge->banner_image)
                        <img src="{{ Storage::url($challenge->banner_image) }}" alt="{{ $challenge->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            <span class="text-lg italic">Tidak ada banner</span>
                        </div>
                    @endif

                    {{-- Badge Status --}}
                    <div class="absolute top-4 right-4">
                        @if($challenge->end_date > now())
                            <span class="px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-full shadow-lg">
                                Sedang Berlangsung
                            </span>
                        @else
                            <span class="px-4 py-2 bg-gray-600 text-white text-sm font-bold rounded-full shadow-lg">
                                Sudah Selesai
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    {{-- Judul & Aksi Utama --}}
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $challenge->title }}</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Dibuat pada: {{ $challenge->created_at->format('d M Y') }}
                            </p>
                        </div>

                        <div class="flex gap-3">
                            {{-- Tombol Lihat Submisi (PENTING) --}}
                            <a href="{{ route('curator.submissions.index', $challenge->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Kelola Submisi
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('curator.challenges.edit', $challenge->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Edit
                            </a>
                        </div>
                    </div>

                    {{-- BAGIAN 2: STATISTIK RINGKAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- Card Total Peserta --}}
                        <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-indigo-600 uppercase">Total Peserta</p>
                                <p class="text-3xl font-bold text-indigo-900 mt-1">{{ $challenge->submissions_count }}</p>
                            </div>
                            <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>

                        {{-- Card Tanggal Mulai --}}
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tanggal Mulai</p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $challenge->start_date->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">Pukul {{ $challenge->start_date->format('H:i') }}</p>
                        </div>

                        {{-- Card Tanggal Selesai --}}
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Batas Akhir (Deadline)</p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $challenge->end_date->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">Pukul {{ $challenge->end_date->format('H:i') }}</p>
                        </div>
                    </div>

                    {{-- BAGIAN 3: DETAIL KONTEN --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        {{-- Kolom Kiri: Deskripsi --}}
                        <div class="lg:col-span-2 space-y-8">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Deskripsi Challenge</h3>
                                <div class="prose max-w-none text-gray-600 whitespace-pre-line leading-relaxed">
                                    {{ $challenge->description }}
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Peraturan (Rules)</h3>
                                <div class="bg-red-50 p-6 rounded-lg border-l-4 border-red-400 text-gray-700 whitespace-pre-line">
                                    {{ $challenge->rules }}
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Hadiah (Looping Array) --}}
                        <div class="lg:col-span-1">
                            <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200 sticky top-6">
                                <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    Daftar Hadiah
                                </h3>

                                <ul class="space-y-4">
                                    @if(is_array($challenge->prizes) || is_object($challenge->prizes))
                                        @foreach($challenge->prizes as $index => $prize)
                                            <li class="flex items-start bg-white p-3 rounded-lg shadow-sm border border-yellow-100">
                                                <div class="flex-shrink-0 mr-3 mt-1">
                                                    @if($index == 0)
                                                        <span class="text-2xl">🥇</span>
                                                    @elseif($index == 1)
                                                        <span class="text-2xl">🥈</span>
                                                    @elseif($index == 2)
                                                        <span class="text-2xl">🥉</span>
                                                    @else
                                                        <span class="text-xl">🏅</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-500 uppercase">Juara {{ $index + 1 }}</p>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $prize }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-500 italic text-sm">Data hadiah tidak valid.</li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Tombol Hapus (Bahaya) --}}
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <form action="{{ route('curator.challenges.destroy', $challenge->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus challenge ini secara permanen? Semua submisi karya juga akan terhapus!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center px-4 py-2 bg-red-100 text-red-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Hapus Challenge Ini
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
