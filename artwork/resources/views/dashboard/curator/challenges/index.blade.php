<x-app-layout>
    {{-- BAGIAN HEADER (TOMBOL) --}}
    {{-- Kita pasang x-data sendiri di sini untuk mengurus tombol aktif/tidak --}}
    <x-slot name="header">
        <div x-data="{
                currentMode: localStorage.getItem('view_mode') || 'grid',
                changeView(mode) {
                    this.currentMode = mode;
                    localStorage.setItem('view_mode', mode); // Simpan pilihan user
                    $dispatch('layout-change', mode); // Kirim sinyal ke konten utama
                }
             }"
             class="flex flex-col md:flex-row justify-between items-center w-full gap-4">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Challenge Saya') }}
            </h2>

            <div class="flex items-center space-x-4">
                {{-- TOMBOL TOGGLE VIEW --}}
                <div class="bg-gray-200 p-1 rounded-lg flex items-center shadow-inner">
                    {{-- Tombol Grid --}}
                    <button @click="changeView('grid')"
                            :class="currentMode === 'grid' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="p-2 rounded-md transition-all duration-200 focus:outline-none"
                            title="Tampilan Grid">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    {{-- Tombol Table --}}
                    <button @click="changeView('table')"
                            :class="currentMode === 'table' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="p-2 rounded-md transition-all duration-200 focus:outline-none"
                            title="Tampilan Tabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Tombol Create --}}
                <a href="{{ route('curator.challenges.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="hidden sm:inline">Buat Challenge</span>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- BAGIAN KONTEN UTAMA --}}
    {{-- Bagian ini mendengarkan sinyal 'layout-change' dari header --}}
    <div class="py-6"
         x-data="{ viewMode: localStorage.getItem('view_mode') || 'grid' }"
         @layout-change.window="viewMode = $event.detail">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-7">

            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded-r flex justify-between items-center" x-data="{ show: true }" x-show="show">
                    <div>
                        <p class="font-bold">Berhasil</p>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            @if($challenges->count() > 0)

                {{-- ================= MODE GRID ================= --}}
                <div x-show="viewMode === 'grid'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @foreach($challenges as $challenge)
                        @php
                            $now = now();
                            $status = ''; $statusClass = '';
                            if ($now < $challenge->start_date) {
                                $status = 'Akan Datang'; $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                            } elseif ($now >= $challenge->start_date && $now <= $challenge->end_date) {
                                $status = 'Sedang Berjalan'; $statusClass = 'bg-green-100 text-green-800 border-green-200';
                            } else {
                                $status = 'Selesai'; $statusClass = 'bg-gray-100 text-gray-800 border-gray-200';
                            }
                        @endphp

                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full border border-gray-200">
                            {{-- Banner --}}
                            <div class="relative h-48 bg-gray-200 group">
                                <img src="{{ Storage::url($challenge->banner_image) ? Storage::url($challenge->banner_image) : asset('images/default_banner.jpg') }}"
                                     alt="{{ $challenge->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 hover:text-indigo-600">
                                    <a href="{{ route('curator.challenges.show', $challenge->id) }}">{{ $challenge->title }}</a>
                                </h3>

                                <div class="text-xs text-gray-500 mb-4 space-y-1">
                                    <p class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}</p>
                                </div>

                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                    <span class="text-sm font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                        {{ $challenge->submissions->count() }} Karya
                                    </span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('curator.challenges.edit', $challenge->id) }}" class="text-gray-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                                        <form action="{{ route('curator.challenges.destroy', $challenge->id) }}" method="POST" onsubmit="return confirm('Hapus challenge ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </div>
                                </div>

                                <a href="{{ route('curator.submissions.index', $challenge->id) }}" class="mt-4 w-full block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold py-2 rounded transition">
                                    Kelola Submissions
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ================= MODE TABLE ================= --}}
                <div x-show="viewMode === 'table'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Challenge</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Partisipan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($challenges as $challenge)
                                    @php
                                        $now = now();
                                        $status = ''; $statusClass = '';
                                        if ($now < $challenge->start_date) {
                                            $status = 'Akan Datang'; $statusClass = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($now >= $challenge->start_date && $now <= $challenge->end_date) {
                                            $status = 'Berjalan'; $statusClass = 'bg-green-100 text-green-800';
                                        } else {
                                            $status = 'Selesai'; $statusClass = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded object-cover bg-gray-100"
                                                         src="{{ Storage::url($challenge->banner_image) ? Storage::url($challenge->banner_image) : asset('images/default_banner.jpg') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('curator.challenges.show', $challenge->id) }}" class="hover:text-indigo-600 hover:underline">
                                                            {{ $challenge->title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-xs">{{ \Carbon\Carbon::parse($challenge->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <span class="font-bold text-gray-900">{{ $challenge->submissions->count() }}</span> Karya
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('curator.submissions.index', $challenge->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">Submissions</a>
                                            <a href="{{ route('curator.challenges.edit', $challenge->id) }}" class="text-gray-600 hover:text-blue-600"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('curator.challenges.destroy', $challenge->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus permanen?');">
                                                @csrf @method('DELETE')
                                                <button class="text-gray-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $challenges->links() }}
                </div>

            @else
                {{-- Empty State (Jika tidak ada data) --}}
                <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada Challenge</h3>
                    <p class="mt-2 text-gray-500 max-w-sm mx-auto">Mulailah dengan membuat kompetisi seni pertama Anda untuk menarik para kreator.</p>
                    <div class="mt-6">
                        <a href="{{ route('curator.challenges.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            + Buat Challenge
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
