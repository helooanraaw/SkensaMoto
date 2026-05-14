<x-sidebar-layout>
    <x-slot name="title">Pengaturan Profil</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <h2 class="text-xl font-black text-blue-950">Pengaturan Profil</h2>
            <p class="text-sm text-slate-500 font-medium">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-[18px] font-bold shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('user.settings.update') }}" method="POST" class="p-8 space-y-8">
                @csrf
                
                <!-- Personal Info Section -->
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-red-600 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                            @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                            @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Nomor WhatsApp</label>
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                                class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all"
                                placeholder="Misal: 08123456789">
                            @error('nomor_telepon') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="space-y-6 pt-4">
                    <h3 class="text-sm font-black text-red-600 uppercase tracking-widest border-b border-slate-100 pb-2">Keamanan (Ganti Password)</h3>
                    
                    <div class="bg-blue-50 p-4 rounded-[14px] text-xs font-bold text-blue-800 mb-4">
                        Kosongkan jika tidak ingin mengganti password.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Password Baru</label>
                            <input type="password" name="password"
                                class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                            @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-blue-950 text-white rounded-full font-bold hover:bg-blue-900 transition-all shadow-lg hover:shadow-blue-900/20">
                        Perbarui Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
