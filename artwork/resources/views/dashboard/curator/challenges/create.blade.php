<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Challenge Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Form mengarah ke route 'store' --}}
                <form action="{{ route('curator.challenges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 1. JUDUL --}}
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Judul Challenge')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- 2. DESKRIPSI --}}
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Deskripsi')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- 3. ATURAN (RULES) --}}
                    <div class="mb-4">
                        <x-input-label for="rules" :value="__('Aturan & Syarat')" />
                        <textarea id="rules" name="rules" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('rules') }}</textarea>
                        <x-input-error :messages="$errors->get('rules')" class="mt-2" />
                    </div>

                    {{-- 4. HADIAH (PRIZES) - DINAMIS --}}
                    <div class="mb-4" x-data="{ winnerCount: 1 }">

                        <div class="flex gap-4 mb-3">
                            <div class="w-1/3">
                                <x-input-label for="winner_count" :value="__('Jumlah Pemenang')" />
                                <select id="winner_count" x-model="winnerCount" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1">1 Pemenang </option>
                                    <option value="2">2 Pemenang </option>
                                    <option value="3">3 Pemenang </option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">Masukkan Detail Hadiah:</p>

                            {{-- Loop Input Sesuai Jumlah Pemenang --}}
                            {{-- Loop Input Sesuai Jumlah Pemenang --}}
                            <template x-for="i in parseInt(winnerCount)" :key="i">
                                <div class="mt-3">
                                    {{-- Gunakan label HTML biasa, bukan x-input-label --}}
                                    <label
                                        class="block font-medium text-sm text-gray-700"
                                        :for="'prize_'+i"
                                        x-text="'Hadiah Juara ' + i">
                                    </label>

                                    {{-- Gunakan input HTML biasa, bukan x-text-input --}}
                                    <input
                                        :id="'prize_'+i"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        type="text"
                                        name="prizes[]"
                                        placeholder="Contoh: Uang Tunai Rp 1.000.000 + Sertifikat"
                                        required
                                    />
                                </div>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('prizes')" class="mt-2" />
                        <x-input-error :messages="$errors->get('prizes.*')" class="mt-2" />
                    </div>

                    {{-- 5. TANGGAL --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Tanggal & Jam Mulai')" />
                            {{-- Ubah type="date" menjadi type="datetime-local" --}}
                            <x-text-input id="start_date" class="block mt-1 w-full"
                                        type="datetime-local"
                                        name="start_date"
                                        :value="old('start_date')"
                                        required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Tanggal & Jam Selesai')" />
                            {{-- Ubah type="date" menjadi type="datetime-local" --}}
                            <x-text-input id="end_date" class="block mt-1 w-full"
                                        type="datetime-local"
                                        name="end_date"
                                        :value="old('end_date')"
                                        required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    {{-- 6. BANNER IMAGE --}}
                    <div class="mb-6">
                        <x-input-label for="banner_image" :value="__('Gambar Banner (Wajib)')" />
                        <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                        <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('curator.challenges.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button>
                            {{ __('Publikasikan Challenge') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
