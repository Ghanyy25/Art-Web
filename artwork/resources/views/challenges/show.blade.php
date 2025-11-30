<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $challenge->title }}
            </h2>
            <a href="{{ route('challenges.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        {{-- 1. HERO SECTION (BANNER) --}}
        <div class="relative w-full bg-gray-900 h-[400px] flex items-center justify-center overflow-hidden">
            @if($challenge->banner_image)
                <img src="{{ Storage::url($challenge->banner_image) }}" alt="{{ $challenge->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-purple-800 to-indigo-900 opacity-80"></div>
            @endif

            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto text-white">
                {{-- Status Badge --}}
                <div class="mb-4">
                    @if($challenge->end_date > now())
                        <span class="px-4 py-1.5 rounded-full bg-green-500/80 backdrop-blur text-white text-sm font-bold shadow-lg uppercase tracking-wider">
                            Sedang Berlangsung
                        </span>
                    @elseif ($challenge->start_date >now())
                        <span class="px-4 py-1.5 rounded-full bg-yellow-500/80 backdrop-blur text-white text-sm font-bold shadow-lg uppercase tracking-wider">
                            Akan Datang
                        </span>
                    @else
                        <span class="px-4 py-1.5 rounded-full bg-red-500/80 backdrop-blur text-white text-sm font-bold shadow-lg uppercase tracking-wider">
                            Selesai
                        </span>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-shadow-lg leading-tight">
                    {{ $challenge->title }}
                </h1>

                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto line-clamp-2">
                    {{ $challenge->description }}
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center text-sm font-medium">
                    <div class="flex items-center justify-center bg-white/10 backdrop-blur rounded-lg px-4 py-2">
                        <i class="fas fa-calendar-alt mr-2 text-yellow-400"></i>
                        <span>{{ $challenge->start_date->format('d M Y') }} - {{ $challenge->end_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-white/10 backdrop-blur rounded-lg px-4 py-2">
                        <i class="fas fa-user-edit mr-2 text-blue-400"></i>
                        <a href="{{route('curator.profile.public',$challenge->curator->id)}}">Curator: {{ $challenge->curator->name }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-12">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KIRI: KONTEN UTAMA --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Tabs Navigation (Opsional, bisa pakai scroll biasa) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 md:p-8 space-y-8">

                            {{-- Deskripsi --}}
                            <section>
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3">
                                        <i class="fas fa-align-left"></i>
                                    </span>
                                    Tentang Challenge
                                </h3>
                                <div class="prose max-w-none text-gray-600 whitespace-pre-line leading-relaxed">
                                    {{ $challenge->description }}
                                </div>
                            </section>

                            <hr class="border-gray-100">

                            {{-- Aturan --}}
                            <section>
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mr-3">
                                        <i class="fas fa-gavel"></i>
                                    </span>
                                    Aturan Main
                                </h3>
                                <div class="bg-red-50 p-5 rounded-xl border border-red-100 text-gray-700 whitespace-pre-line text-sm leading-relaxed">
                                    {{ $challenge->rules }}
                                </div>
                            </section>

                        </div>
                    </div>

                    {{-- BAGIAN GALERI SUBMISI --}}
                    <div id="gallery" class="pt-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 border-l-4 border-indigo-600 pl-4">
                            Karya Peserta ({{ $submissions->total() }})
                        </h3>

                        @if($submissions->isEmpty())
                            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                    <i class="fas fa-paint-brush text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Belum ada karya masuk</h4>
                                <p class="text-gray-500 mt-1">Jadilah yang pertama mengirimkan karya untuk challenge ini!</p>
                            </div>
                        @else
                            {{-- MASONRY GRID --}}
                            <div class="columns-2 sm:columns-3 gap-4 space-y-4">
                                @foreach($submissions as $submission)
                                    <div class="break-inside-avoid mb-4 relative group rounded-xl overflow-hidden shadow-sm hover:shadow-md bg-white transition-shadow duration-300 border border-gray-100">
                                        <img src="{{ Storage::url($submission->artwork->file_path) }}"
                                             alt="{{ $submission->artwork->title }}"
                                             class="w-full h-auto object-cover">

                                        <a href="{{ route('artworks.show', $submission->artwork->id) }}" class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></a>

                                        <div class="p-3 bg-white">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $submission->user->profile_picture ? Storage::url($submission->user->profile_picture) : asset('images/default.png') }}" class="w-6 h-6 rounded-full object-cover">
                                                <span class="text-xs font-medium text-gray-700 truncate">{{ $submission->user->name }}</span>
                                            </div>
                                        </div>

                                        {{-- Badge Juara (Jika ada) --}}
                                        @if($submission->placement)
                                            <div class="absolute top-2 left-2 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                                <i class="fas fa-trophy"></i> Juara {{ $submission->placement }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8">
                                {{ $submissions->links() }}
                            </div>
                        @endif
                    </div>

                </div>

                {{-- KANAN: SIDEBAR (HADIAH & CTA) --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Card Hadiah --}}
                    <div class="bg-white rounded-xl shadow-lg border border-yellow-100 overflow-hidden relative">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                            <h3 class="text-white font-bold text-lg flex items-center">
                                <i class="fas fa-trophy mr-2 text-yellow-100"></i> Hadiah Pemenang
                            </h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-4">
                                @if(is_array($challenge->prizes))
                                    @foreach($challenge->prizes as $index => $prize)
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-50 border border-yellow-200 flex items-center justify-center mr-3 text-lg">
                                                @if($index == 0) 🥇
                                                @elseif($index == 1) 🥈
                                                @elseif($index == 2) 🥉
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Juara {{ $index + 1 }}</p>
                                                <p class="text-sm font-semibold text-gray-800">{{ $prize }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-sm text-gray-500 italic">Detail hadiah belum diatur.</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if(isset($winners) && $winners->count() > 0)
        <div class="bg-gradient-to-b from-yellow-50 to-white rounded-xl shadow-md border border-yellow-200 overflow-hidden relative p-6 md:p-8 mb-8">

            {{-- Hiasan Background --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-yellow-300 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-orange-300 rounded-full opacity-20 blur-xl"></div>

            <div class="relative z-10 text-center mb-8">
                <h3 class="text-2xl font-extrabold text-gray-900 flex items-center justify-center gap-2">
                    <i class="fas fa-crown text-yellow-500 text-3xl"></i> Hall of Fame
                </h3>
                <p class="text-gray-500 text-sm">Selamat kepada para pemenang challenge ini!</p>
            </div>

            {{-- Grid Podium --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end justify-center">
                @foreach($winners as $winner)
                    {{-- Logika Tampilan --}}
                    <div class="relative flex flex-col items-center
                        {{ $winner->placement == 1 ? 'order-first md:order-2 scale-105 z-10' : ($winner->placement == 2 ? 'md:order-1' : 'md:order-3') }}">

                        {{-- Card Artwork Pemenang --}}
                        <div class="group relative w-full bg-white rounded-xl shadow-lg overflow-hidden border-2
                            {{ $winner->placement == 1 ? 'border-yellow-400 ring-4 ring-yellow-100' :
                              ($winner->placement == 2 ? 'border-gray-300' : 'border-orange-300') }}">

                            {{-- Link ke Detail Artwork --}}
                            <a href="{{ route('artworks.show', $winner->artwork->id) }}">
                                <div class="aspect-w-4 aspect-h-3 bg-gray-100">
                                    <img src="{{ Storage::url($winner->artwork->file_path) }}"
                                         alt="Juara {{ $winner->placement }}"
                                         class="w-full h-48 {{ $winner->placement == 1 ? 'h-56' : '' }} object-cover transition duration-500 group-hover:scale-110">
                                </div>
                            </a>

                            {{-- Badge Juara --}}
                            <div class="absolute top-2 left-2 px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm
                                {{ $winner->placement == 1 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' :
                                  ($winner->placement == 2 ? 'bg-gradient-to-r from-gray-400 to-gray-600' : 'bg-gradient-to-r from-orange-400 to-orange-600') }}">
                                Juara {{ $winner->placement }}
                            </div>
                        </div>

                        {{-- Info User --}}
                        <div class="mt-4 text-center">
                            <div class="flex flex-col items-center">
                                <img src="{{ $winner->user->profile_picture ? Storage::url($winner->user->profile_picture) : asset('images/default.png') }}"
                                     class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover mb-2">
                                <span class="font-bold text-gray-800 text-sm md:text-base">{{ $winner->user->name }}</span>
                                <span class="text-xs text-gray-500 truncate max-w-[150px] italic">"{{ $winner->artwork->title }}"</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

                    {{-- Card Action (CTA Submit) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center sticky top-6">
                        <h4 class="font-bold text-gray-900 mb-2">Tertarik Ikutan?</h4>

                        @auth
                            @if(Auth::user()->role === 'member')
                                @if($challenge->end_date > now())
                                    <p class="text-sm text-gray-500 mb-4">
                                        Submit karya terbaikmu sebelum <br>
                                        <span class="font-semibold text-red-500">{{ $challenge->end_date->format('d M Y, H:i') }}</span>
                                    </p>

                                    @if ($existingSubmission)
                                        <div class="bg-green-50 p-3 rounded-lg text-green-700 text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> Anda sudah mengirimkan karya untuk challenge ini.
                                        </div>
                                    @else
                                        <a href="{{ route('challenge.submit.create', $challenge->id) }}"
                                           class="block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                                            Upload Karya Sekarang
                                        </a>
                                    @endif
                                @elseif($challenge->start_date > now())
                                    <div class="bg-yellow-50 p-3 rounded-lg text-yellow-700 text-sm">
                                        <i class="fas fa-clock mr-1"></i> Challenge Belum Dimulai
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-3 rounded-lg text-gray-500 text-sm font-medium">
                                        <i class="fas fa-lock mr-1"></i> Challenge Telah Berakhir
                                    </div>
                                @endif
                            @else
                                <div class="bg-blue-50 p-3 rounded-lg text-blue-700 text-sm">
                                    Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>. <br>
                                    Hanya Member yang dapat mengikuti challenge.
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 mb-4">
                                Login atau Daftar sebagai Member untuk mengirimkan karya Anda.
                            </p>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}" class="py-2 px-4 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="py-2 px-4 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
                                    Daftar
                                </a>
                            </div>
                        @endauth
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
