<x-guest-layout>
    <div class="flex h-screen w-full overflow-hidden">

        <div class="hidden lg:flex w-1/2 bg-gray-900 relative items-center justify-center">
            <div class="absolute inset-0 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1547891654-e66ed7ebb968?q=80&w=2070&auto=format&fit=crop"
                     alt="Art Background"
                     class="w-full h-full object-cover opacity-60">
            </div>
            <div class="relative z-10 px-10 text-center">
                <h2 class="text-4xl font-bold text-white tracking-tight mb-2">Selamat Datang Kembali</h2>
                <p class="text-gray-300 text-lg">Lanjutkan perjalanan kreatif Anda dan temukan inspirasi baru hari ini.</p>
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
                    {{-- Logo --}}
                    <a href="{{ route('explore') }}" class="flex justify-center lg:justify-start">
                        <x-application-logo class="w-10 h-12 fill-current text-gray-900" />
                    </a>
                    <h2 class="mt-6 text-3xl font-bold text-gray-900">Masuk ke Akun</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition">
                            Daftar sekarang
                        </a>
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" />
                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 text-base">
                            {{ __('Masuk') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
