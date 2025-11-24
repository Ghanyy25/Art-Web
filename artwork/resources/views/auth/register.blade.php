<x-guest-layout>
    <div class="flex h-screen w-full overflow-hidden">

        <div class="hidden lg:flex w-1/2 bg-gray-900 relative items-center justify-center">
            <div class="absolute inset-0 overflow-hidden">
                {{-- Gambar yang berbeda untuk Register --}}
                <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?q=80&w=2071&auto=format&fit=crop"
                     alt="Art Background Register"
                     class="w-full h-full object-cover opacity-60">
            </div>
            <div class="relative z-10 px-10 text-center">
                <h2 class="text-4xl font-bold text-white tracking-tight mb-2">Bergabung dengan Komunitas</h2>
                <p class="text-gray-300 text-lg">Tunjukkan karya Anda, apresiasi seni, dan terhubung dengan kreator lain.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center p-8 sm:p-12 md:p-16 relative overflow-y-auto">

            <a href="{{ route('explore') }}" class="absolute top-8 left-8 text-gray-400 hover:text-gray-900 transition flex items-center gap-2 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Explore
            </a>

            <div class="w-full max-w-md space-y-8">
                <div class="text-center lg:text-left">
                    <a href="{{ route('explore') }}" class="flex justify-center lg:justify-start">
                        <x-application-logo class="w-12 h-12 fill-current text-gray-900" />
                    </a>
                    <h2 class="mt-6 text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition">
                            Masuk disini
                        </a>
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="block mt-1 w-full rounded-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Seniman Keren" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full justify-center py-3 text-base">
                            {{ __('Daftar Sekarang') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
