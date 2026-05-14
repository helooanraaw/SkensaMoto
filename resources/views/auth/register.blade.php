<x-guest-layout>
    <!-- Logo & Header -->
    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center justify-center gap-2 mb-6">
            <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-10 w-auto" />
            <span class="font-extrabold text-2xl tracking-tight text-blue-950">Skensa<span class="text-red-600">Moto</span></span>
        </a>
        <h2 class="text-2xl font-bold text-blue-950 mb-2">Buat Akun Baru</h2>
        <p class="text-sm text-slate-500 font-light px-4">Bergabung dengan SkensaMoto untuk mempermudah servis motor Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-blue-950 mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan Nama Anda" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-blue-950 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan Email Anda" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Nomor Telepon (WA) -->
        <div>
            <label for="nomor_telepon" class="block text-sm font-bold text-blue-950 mb-1.5">Nomor Telepon (WhatsApp)</label>
            <input id="nomor_telepon" type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required autocomplete="tel" placeholder="Contoh: 081234567890" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('nomor_telepon')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-blue-950 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan Password Anda" class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-blue-950 mb-1.5">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Masukkan Konfirmasi Password Anda"  class="w-full border-slate-200 rounded-[14px] px-4 py-3 bg-slate-50 text-blue-950 font-normal focus:ring-red-500 focus:border-red-500 transition-colors">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 pt-2">
            <button type="submit" class="w-full flex items-center justify-center px-6 py-3.5 bg-red-600 text-white rounded-full font-bold uppercase tracking-widest hover:bg-red-700 active:bg-red-800 transition-colors duration-200">
                DAFTAR
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500 font-light">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-blue-950 hover:text-red-600 transition-colors">Masuk disini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
