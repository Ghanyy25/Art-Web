<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Challenges') }}
            </h2>
            <a href="{{ route('curator.challenges.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                + Create New Challenge
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submissions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($challenges as $challenge)
                            <tr>
                                <td class="px-6 py-4 font-medium"><a href="{{ route('curator.challenges.show', $challenge->id) }}">{{$challenge->title }} </a></td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $challenge->start_date }} to {{ $challenge->end_date }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $challenge->submissions->count() }} Entries
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('curator.submissions.index', $challenge->id) }}" class="text-green-600 hover:text-green-900 mr-3">Manage Submissions</a>
                                    <a href="{{ route('curator.challenges.edit', $challenge->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('curator.challenges.destroy', $challenge->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
