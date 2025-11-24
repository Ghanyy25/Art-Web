<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Content Moderation Queue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($reports->isEmpty())
                    <p class="text-gray-500 text-center">No reports found. Good job!</p>
                @else
                    <div class="grid gap-6">
                        @foreach($reports as $report)
                            <div class="border rounded-lg p-4 bg-gray-50 flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-red-600 mb-1">Report Reason: {{ $report->reason }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">Reported by: {{ $report->reporteruser->name }}</p>
                                    <div class="bg-white p-3 border rounded">
                                        <p class="font-semibold">Content: {{ $report->artwork->title }}</p>
                                        <p class="text-xs text-gray-500">Creator: {{ $report->artwork->user->name }}</p>
                                        <a href="{{ route('artworks.show', $report->artwork->id) }}" target="_blank" class="text-blue-500 text-sm underline mt-1 block">View Content</a>
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center gap-2 min-w-[150px]">
                                    <form method="POST" action="{{ route('admin.moderation.dismiss', $report->id) }}">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                            Dismiss Report
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.moderation.takeDown', $report->id) }}" onsubmit="return confirm('Delete this content permanently?');">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Take Down
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
