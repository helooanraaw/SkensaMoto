<x-guest-layout>
    <!-- Judul Sapaan -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-blue-950">Selamat Datang Kembali!</h2>
        <p class="text-sm text-slate-500 mt-2">Masuk ke akun Anda untuk melakukan reservasi servis motor.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center justify-center mt-8 space-y-4">
            <x-primary-button class="w-full">
                {{ __('Masuk Sekarang') }}
            </x-primary-button>
            
            <div class="flex items-center justify-between w-full text-sm">
                @if (Route::has('password.request'))
                    <a class="font-semibold text-slate-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif

                <a class="font-semibold text-blue-950 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" href="{{ route('register') }}">
                    Belum punya akun? Daftar
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
