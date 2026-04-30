<x-guest-layout>
    <!-- Judul Sapaan -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-blue-950">Buat Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-2">Bergabung dengan SkensaMoto untuk mempermudah servis motor Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center justify-center mt-8 space-y-4">
            <x-primary-button class="w-full">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
            
            <div class="flex items-center justify-center w-full text-sm">
                <a class="font-semibold text-slate-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" href="{{ route('login') }}">
                    Sudah punya akun? Masuk
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
