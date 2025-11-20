<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">My Artworks</h1>
            <a href="{{ route('artworks.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Upload New</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-green-700">{{ session('success') }}</div>
        @endif

        @if($artworks->isEmpty())
            <div class="p-6 bg-white rounded shadow">You have no artworks yet. <a href="{{ route('artworks.create') }}" class="text-indigo-600">Upload your first artwork</a>.</div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($artworks as $artwork)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-48 bg-gray-100">
                            <img src="{{ Storage::url($artwork->file_path) }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1 whitespace-pre-wrap break-words">{{ Str::limit($artwork->description,25) }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-xs text-gray-400">{{ $artwork->created_at->format('d M Y') }}</div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('artworks.edit', $artwork->id) }}" class="text-sm px-3 py-1 bg-yellow-100 rounded text-yellow-800">Edit</a>

                                    <form action="{{ route('artworks.destroy', $artwork->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this artwork?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm px-3 py-1 bg-red-100 rounded text-red-700">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
