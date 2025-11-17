<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:flex md:space-x-8">

                    <div class="w-full md:w-2/3 mb-6 md:mb-0">
                        <img src="{{ Storage::url($artwork->file_path) }}" alt="{{ $artwork->title }}" class="w-full h-auto rounded-lg shadow-md">
                    </div>

                    <div class="w-full md:w-1/3">

                        <div class="flex items-center mb-4">
                            <a href="{{ route('profile.show', $artwork->user->id) }}">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $artwork->user->profile_picture ? Storage::url($artwork->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) }}"
                                     alt="{{ $artwork->user->name }}">
                            </a>
                            <div class="ml-3">
                                <a href="{{ route('profile.show', $artwork->user->id) }}" class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $artwork->user->name }}
                                </a>
                                <span class="text-sm text-gray-500 block">Kreator</span>
                            </div>
                        </div>

                        <p class="text-sm text-gray-700 mb-4 whitespace-pre-wrap">{{ $artwork->description }}</p>

                        <div class="mb-6">
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                                {{ $artwork->category->name }}
                            </span>
                            @if($artwork->tags)
                                <div class="mt-2 space-x-2">
                                    @foreach(explode(',', $artwork->tags) as $tag)
                                        <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-medium px-2 py-1 rounded-full">
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @auth
                            <div class="flex space-x-2 mb-6">
                                <form action="{{ route('like.toggle', $artwork->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium border
                                        {{ $isLiked ? 'bg-pink-500 text-white border-pink-500' : 'bg-white text-pink-500 border-pink-300 hover:bg-pink-50' }}">
                                        <i class="fas fa-heart mr-2"></i> {{ $isLiked ? 'Unlike' : 'Like' }}
                                    </button>
                                </form>

                                <form action="{{ route('favorite.toggle', $artwork->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium border
                                        {{ $isFavorited ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-yellow-500 border-yellow-300 hover:bg-yellow-50' }}">
                                        <i class="fas fa-star mr-2"></i> {{ $isFavorited ? 'Unsave' : 'Save' }}
                                    </button>
                                </form>
                            </div>

                            <form action="{{ route('report.store', $artwork->id) }}" method="POST" x-data="{ open: false }">
                                @csrf
                                <a href="#" @click.prevent="open = !open" class="text-xs text-gray-500 hover:text-red-600 hover:underline">
                                    <i class="fas fa-flag"></i> Laporkan karya ini
                                </a>
                                <div x-show="open" class="mt-2 border border-gray-200 p-2 rounded-md">
                                    <x-input-label for="reason" value="Alasan Laporan" />
                                    <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" placeholder="Misal: Plagiat, SARA, dll" required />
                                    <x-primary-button class="mt-2">Kirim Laporan</x-primary-button>
                                </div>
                            </form>
                        @endauth

                        @guest
                            <div class="p-4 bg-gray-50 rounded-md text-sm text-gray-700">
                                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Login</a> atau
                                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">Register</a> untuk Like, Comment, dan Save.
                            </div>
                        @endguest

                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Komentar ({{ $artwork->comments->count() }})</h3>

                    @auth
                        <form action="{{ route('comment.store', $artwork->id) }}" method="POST" class="mb-6">
                            @csrf
                            <x-input-label for="body" value="Tinggalkan Komentar" class="sr-only" />
                            <textarea id="body" name="body" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tulis komentar Anda..."></textarea>
                            <x-primary-button class="mt-2">Kirim</x-primary-button>
                        </form>
                    @endauth

                    <div class="space-y-4">
                        @forelse($artwork->comments as $comment)
                            <div class="flex space-x-3">
                                <a href="{{ route('profile.show', $comment->user->id) }}">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         src="{{ $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                         alt="{{ $comment->user->name }}">
                                </a>
                                <div class="flex-1 bg-gray-50 p-3 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('profile.show', $comment->user->id) }}" class="text-sm font-semibold text-gray-900 hover:underline">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1">{{ $comment->body }}</p>

                                    @auth
                                        @if(Auth::id() == $comment->user_id)
                                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus komentar ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
