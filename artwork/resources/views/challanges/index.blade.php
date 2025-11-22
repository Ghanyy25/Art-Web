<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Challenges & Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($challenges as $challenge)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($challenge->banner_image)
                            <img src="{{ asset('storage/' . $challenge->banner_image) }}" alt="{{ $challenge->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                No Banner
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">{{ $challenge->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $challenge->description }}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                <span>Ends: {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}</span>
                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ $challenge->status }}</span>
                            </div>
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="block text-center bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-10">
                        No active challenges at the moment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
