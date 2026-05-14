<x-guest-layout>
    <!-- Logo & Header -->
    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center justify-center gap-2 mb-6">
            <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-10 w-auto" />
            <span class="font-extrabold text-2xl tracking-tight text-blue-950">Skensa<span class="text-red-600">Moto</span></span>
        </a>
        <h2 class="text-2xl font-bold text-blue-950 mb-2">Selamat Datang!</h2>
        <p class="text-sm text-slate-500 font-light px-4">Masuk ke akun Anda untuk melakukan reservasi servis motor.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-blue-950 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan Email Anda" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-blue-950 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan Password Anda" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                <span class="ml-2 text-sm text-slate-500 font-light">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-slate-500 hover:text-blue-950 transition-colors" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div class="mt-6 pt-2">
            <button type="submit" class="w-full flex items-center justify-center px-6 py-3.5 bg-red-600 text-white rounded-full font-bold uppercase tracking-widest hover:bg-red-700 active:bg-red-800 transition-colors duration-200">
                MASUK
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500 font-light">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-blue-950 hover:text-red-600 transition-colors">Daftar disini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
