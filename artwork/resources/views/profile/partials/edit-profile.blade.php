<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil akun, foto, bio, dan tautan sosial media Anda.") }}
        </p>
    </header>


    {{-- PERHATIAN: Tambahkan enctype="multipart/form-data" untuk upload file --}}
    <form method="post" action="{{ route('profile.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf

        {{-- 1. FOTO PROFIL --}}
        <div>
            <x-input-label for="profile_picture" :value="__('Foto Profil')" />

            <div class="mt-2 flex items-center gap-4">
                {{-- Preview Foto Saat Ini --}}
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                         alt="Foto Profil"
                         class="w-20 h-20 rounded-full object-cover border border-gray-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif

                {{-- Input File --}}
                <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        {{-- 2. NAMA --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- 3. EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- 4. BIO / DESKRIPSI SINGKAT --}}
        <div>
            <x-input-label for="bio" :value="__('Bio / Deskripsi Singkat')" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                      placeholder="Ceritakan sedikit tentang diri Anda sebagai kreator...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        {{-- 5. TAUTAN EKSTERNAL (SOSMED) --}}
        <div class="space-y-4 border-t border-gray-100 pt-4">
            <h3 class="text-md font-medium text-gray-900">{{ __('Tautan Sosial Media') }}</h3>

            {{-- Instagram --}}
            <div>
                <x-input-label for="instagram" :value="__('Instagram URL')" />
                <x-text-input id="instagram" name="external_links[instagram]" type="url" class="mt-1 block w-full"
                    :value="old('external_links.instagram', $user->external_links['instagram'] ?? '')"
                    placeholder="https://instagram.com/username" />
                <x-input-error class="mt-2" :messages="$errors->get('external_links.instagram')" />
            </div>

            {{-- Website Pribadi --}}
            <div>
                <x-input-label for="website" :value="__('Website Pribadi')" />
                <x-text-input id="website" name="external_links[website]" type="url" class="mt-1 block w-full"
                    :value="old('external_links.website', $user->external_links['website'] ?? '')"
                    placeholder="https://portofolio-saya.com" />
                <x-input-error class="mt-2" :messages="$errors->get('external_links.website')" />
            </div>
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
