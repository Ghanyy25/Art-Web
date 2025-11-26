<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                @if($challenge->banner_image)
                    <img src="{{ asset('storage/' . $challenge->banner_image) }}" alt="{{ $challenge->title }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Description</h3>
                    <p class="text-gray-700 mb-6">{{ $challenge->description }}</p>

                    <h4 class="text-xl font-bold mb-2">Rules</h4>
                    <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $challenge->rules }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded">
                        <div>
                            <span class="font-bold text-gray-600">Prize:</span>
                            <p class="text-lg text-green-600 font-bold">{{ $challenge->prize }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-gray-600">Timeline:</span>
                            <p>{{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'member' && \Carbon\Carbon::now()->lte($challenge->end_date))
                             <div class="mt-6 border-t pt-6">
                                <h4 class="text-lg font-bold mb-4">Join this Challenge</h4>
                                <a href="{{ route('challenge.submit.create', $challenge->id) }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700">
                                    Submit Your Work
                                </a>
                             </div>
                        @endif
                    @endauth
                </div>
            </div>

            <h3 class="text-2xl font-bold mb-4 text-gray-800">Submissions Gallery</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($challenge->submissions as $submission)
                    <div class="bg-white rounded-lg shadow overflow-hidden relative">
                        <img src="{{ asset('storage/' . $submission->artwork->image_path) }}" class="w-full h-48 object-cover">
                        <div class="p-3">
                            <p class="font-bold truncate">{{ $submission->artwork->title }}</p>
                            <p class="text-xs text-gray-500">by {{ $submission->artwork->user->name }}</p>
                        </div>
                        @if($submission->is_winner)
                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow">
                                🏆 Winner
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No submissions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
