<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Submissions: {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($submissions as $submission)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <img src="{{ asset('storage/' . $submission->artwork->image_path) }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold truncate">{{ $submission->artwork->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">by {{ $submission->artwork->user->name }}</p>

                            @if($submission->is_winner)
                                <div class="w-full bg-yellow-100 text-yellow-800 text-center py-2 rounded font-bold">
                                    🏆 Winner
                                </div>
                            @else
                                <form method="POST" action="{{ route('curator.submissions.selectWinner', $submission->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                                        Mark as Winner
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
