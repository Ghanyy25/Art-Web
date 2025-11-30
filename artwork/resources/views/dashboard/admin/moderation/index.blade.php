<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moderasi Konten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konten</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reports as $report)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->reporter->name ?? 'Unknown User' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($report->artwork_id)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Artwork</span>
                                            @elseif($report->comment_id)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Komentar</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($report->artwork)
                                                {{-- Jika yang dilaporkan Artwork --}}
                                                <a href="{{ route('artworks.show', $report->artwork->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline" target="_blank">
                                                    {{ Str::limit($report->artwork->title, 30) }}
                                                </a>
                                                <div class="text-xs text-gray-400">oleh: {{ $report->artwork->user->name ?? 'N/A' }}</div>

                                            @elseif($report->comment)
                                                {{-- Jika yang dilaporkan Komentar --}}
                                                <div class="italic">"{{ Str::limit($report->comment->body, 40) }}"</div>
                                                <div class="text-xs text-gray-400">oleh: {{ $report->comment->user->name ?? 'N/A' }}</div>
                                                @if($report->comment->artwork)
                                                    <a href="{{ route('artworks.show', $report->comment->artwork_id) }}" class="text-xs text-indigo-500 hover:underline">Lihat Konteks</a>
                                                @endif

                                            @else
                                                <span class="text-red-500 italic">Konten telah dihapus</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($report->reason, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($report->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($report->status == 'resolved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dismissed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                {{-- Tombol Dismiss (Abaikan) --}}
                                                <form action="{{ route('admin.moderation.dismiss', $report->id) }}" method="POST" onsubmit="return confirm('Abaikan laporan ini?');">
                                                    @csrf
                                                    <button type="submit" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-xs">
                                                        Abaikan
                                                    </button>
                                                </form>

                                                {{-- Tombol Take Down (Hapus Konten) --}}
                                                @if($report->status !== 'resolved')
                                                    <form action="{{ route('admin.moderation.takeDown', $report->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus konten ini? Aksi ini tidak dapat dibatalkan.');">
                                                        @csrf
                                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs shadow-sm">
                                                            Hapus Konten
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada laporan yang perlu dimoderasi saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
