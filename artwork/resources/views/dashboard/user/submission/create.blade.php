<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit to: {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('challenge.submit.store', $challenge->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($myArtworks as $artwork)
                        <label class="cursor-pointer relative">
                            <input type="radio" name="artwork_id" value="{{ $artwork->id }}" class="peer sr-only" required>
                            <div class="border-4 border-transparent peer-checked:border-indigo-500 rounded-lg overflow-hidden shadow hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $artwork->image_path) }}" class="w-full h-40 object-cover">
                                <div class="p-2 bg-white text-center font-bold text-sm">
                                    {{ $artwork->title }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    <x-primary-button class="text-lg px-8 py-3">
                        {{ __('Submit Selected Artwork') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
