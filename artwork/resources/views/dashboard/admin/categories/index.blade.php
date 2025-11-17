<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ editModal: false, categoryName: '', categoryId: 0, deleteModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Kategori Baru</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <x-input-label for="name" :value="__('Nama Kategori')" class="sr-only" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Nama Kategori (mis: Fotografi)" required />
                        </div>
                        <x-primary-button>
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kategori</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-2">
                                        <button
                                            @click="editModal = true; categoryName = '{{ $category->name }}'; categoryId = {{ $category->id }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Anda yakin ingin menghapus kategori ini? Karya yang menggunakan kategori ini akan di-set ke NULL.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="editModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
             style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="editModal = false"
                 class="bg-white rounded-lg shadow-xl w-full max-w-md p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">

                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Kategori</h3>

                <form :action="'/admin/categories/' + categoryId" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="edit_name" :value="__('Nama Kategori')" />
                        <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full" x-model="categoryName" required />
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button @click.prevent="editModal = false">
                            {{ __('Batal') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
