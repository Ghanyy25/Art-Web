<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Challenge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Form Update ke route 'update' dengan ID challenge --}}
                <form action="{{ route('curator.challenges.update', $challenge->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- PENTING: Untuk update data --}}

                    {{-- 1. JUDUL --}}
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Judul Challenge')" />
                        {{-- Value diambil dari old input (jika gagal validasi) ATAU data database ($challenge->title) --}}
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $challenge->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- 2. DESKRIPSI --}}
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Deskripsi')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $challenge->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- 3. RULES --}}
                    <div class="mb-4">
                        <x-input-label for="rules" :value="__('Aturan & Syarat')" />
                        <textarea id="rules" name="rules" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('rules', $challenge->rules) }}</textarea>
                        <x-input-error :messages="$errors->get('rules')" class="mt-2" />
                    </div>

                    {{-- 4. HADIAH (PRIZES) - EDIT --}}
                    {{-- Inisialisasi winnerCount dengan jumlah data yang ada di database --}}
                    <div class="mb-4" x-data="{ winnerCount: {{ count($challenge->prizes ?? []) ?: 1 }} }">

                        <div class="flex gap-4 mb-3">
                            <div class="w-1/3">
                                <x-input-label for="winner_count" :value="__('Jumlah Pemenang')" />
                                <select id="winner_count" x-model="winnerCount" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1">1 Pemenang (Juara 1)</option>
                                    <option value="2">2 Pemenang (Juara 1 & 2)</option>
                                    <option value="3">3 Pemenang (Juara 1, 2 & 3)</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">Detail Hadiah:</p>

                            {{-- Kita loop manual pakai Blade untuk mengisi value lama --}}
                            {{-- Menggunakan x-show untuk menyembunyikan input jika winnerCount dikurangi --}}

                            @for ($i = 0; $i < 3; $i++)
                                <div x-show="{{ $i + 1 }} <= winnerCount">
                                        <x-input-label :for="'prize_'.$i" :value="'Hadiah Juara ' . ($i + 1)" />
                                        <x-text-input
                                            :id="'prize_'.$i"
                                            class="block mt-1 w-full"
                                            type="text"
                                            name="prizes[]"
                                            :value="$challenge->prizes[$i] ?? ''"
                                            placeholder="Masukkan hadiah..."
                                            {{-- FIX: Gunakan x-bind:disabled agar Alpine yang mengevaluasi logic JS-nya --}}
                                            x-bind:disabled="{{ $i + 1 }} > winnerCount"
                                        />
                                    </div>
                            @endfor
                        </div>
                    </div>

                    {{-- 5. TANGGAL --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Tanggal & Jam Mulai')" />

                            {{-- Perhatikan format('Y-m-d\TH:i') --}}
                            <x-text-input id="start_date" class="block mt-1 w-full"
                                        type="datetime-local"
                                        name="start_date"
                                        :value="old('start_date', $challenge->start_date->format('Y-m-d\TH:i'))"
                                        required />

                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Tanggal & Jam Selesai')" />

                            {{-- Perhatikan format('Y-m-d\TH:i') --}}
                            <x-text-input id="end_date" class="block mt-1 w-full"
                                        type="datetime-local"
                                        name="end_date"
                                        :value="old('end_date', $challenge->end_date->format('Y-m-d\TH:i'))"
                                        required />

                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    {{-- 6. BANNER (Opsional saat Edit) --}}
                    <div class="mb-6">
                        <x-input-label for="banner_image" :value="__('Ganti Banner (Opsional)')" />

                        {{-- Tampilkan Banner Lama --}}
                        @if($challenge->banner_image)
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 mb-1">Banner saat ini:</p>
                                <img src="{{ Storage::url($challenge->banner_image) }}" alt="Current Banner" class="h-32 w-auto object-cover rounded border">
                            </div>
                        @endif

                        <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('curator.challenges.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
