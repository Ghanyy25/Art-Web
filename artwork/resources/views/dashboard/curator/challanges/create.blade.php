<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Challenge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('curator.challenges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Challenge Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea name="description" class="w-full border-gray-300 rounded-md shadow-sm" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="rules" :value="__('Rules')" />
                        <textarea name="rules" class="w-full border-gray-300 rounded-md shadow-sm" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="prize" :value="__('Prize')" />
                        <x-text-input id="prize" class="block mt-1 w-full" type="text" name="prize" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" required />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" required />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="banner_image" :value="__('Banner Image')" />
                        <input type="file" name="banner_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Create Challenge') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
