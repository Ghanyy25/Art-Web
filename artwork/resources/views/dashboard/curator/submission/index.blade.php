<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 w-full">
            {{-- Judul Halaman --}}
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Kelola Submisi & Pemenang') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Challenge: <strong>{{ $challenge->title }}</strong>
                </p>
            </div>

            <a href="{{ route('curator.challenges.show', $challenge->id) }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                &larr; Kembali ke Detail
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($challenge->end_date->isFuture())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 rounded-r-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Perhatian:</strong> Challenge ini masih berlangsung hingga {{ $challenge->end_date->format('d M Y') }}.
                                <br>Anda bisa memilih pemenang sementara, namun disarankan menunggu hingga batas waktu berakhir.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- PESAN SUKSES / ERROR --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- KONTEN UTAMA --}}
            @if($submissions->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center border border-gray-200">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Peserta</h3>
                    <p class="text-gray-500 mt-1">Belum ada member yang mengirimkan karya untuk challenge ini.</p>
                </div>
            @else

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($submissions as $submission)
                        <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border-2 {{ $submission->placement ? 'border-yellow-400 ring-4 ring-yellow-50' : 'border-transparent' }}">

                            {{-- Header Kartu (User Info) --}}
                            <div class="p-4 flex items-center gap-3 border-b border-gray-100 bg-gray-50">
                                <img src="{{ $submission->user->profile_picture ? Storage::url($submission->user->profile_picture) : asset('images/default.png') }}" class="w-8 h-8 rounded-full object-cover bg-white border">
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $submission->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            {{-- Gambar Karya --}}
                            <div class="relative aspect-square bg-gray-200 group">
                                <img src="{{ Storage::url($submission->artwork->file_path) }}"
                                     alt="Artwork"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                                {{-- Link Lihat Full --}}
                                <a href="{{ route('artworks.show', $submission->artwork->id) }}" target="_blank" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span class="bg-white text-gray-800 px-4 py-2 rounded-full text-xs font-bold shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                        Lihat Detail
                                    </span>
                                </a>

                                {{-- Badge JUARA (Overlay di Gambar) --}}
                                @if($submission->placement)
                                    <div class="absolute top-2 left-2 bg-yellow-400 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1 z-10 border border-yellow-500">
                                        <span class="text-lg">
                                            @if($submission->placement == 1) 🥇
                                            @elseif($submission->placement == 2) 🥈
                                            @elseif($submission->placement == 3) 🥉
                                            @else 🏅
                                            @endif
                                        </span>
                                        <span class="uppercase tracking-wider">Juara {{ $submission->placement }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Footer Kartu (Form Penilaian) --}}
                            <div class="p-4 bg-white">
                                <form action="{{ route('curator.submissions.selectWinner', $submission->id) }}" method="POST">
                                    @csrf

                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wide">
                                        Pilih Peringkat:
                                    </label>

                                    <div class="flex gap-2">
                                        <select name="placement" class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                            <option value="">-- Peserta Biasa --</option>

                                            {{-- LOOPING HADIAH (DINAMIS) --}}
                                            @if(is_array($challenge->prizes) || is_object($challenge->prizes))
                                                @foreach($challenge->prizes as $index => $prize)
                                                    @php $rank = $index + 1; @endphp
                                                    <option value="{{ $rank }}" {{ $submission->placement == $rank ? 'selected' : '' }}>
                                                        @if($rank == 1) 🥇 Juara 1
                                                        @elseif($rank == 2) 🥈 Juara 2
                                                        @elseif($rank == 3) 🥉 Juara 3
                                                        @else 🏅 Juara {{ $rank }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>

                                        <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition shadow-sm flex items-center justify-center" title="Simpan Status">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
