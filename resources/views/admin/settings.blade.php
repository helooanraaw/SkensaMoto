<x-sidebar-layout>
    <x-slot name="title">Pengaturan Bengkel</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <h2 class="text-xl font-black text-blue-950">Pengaturan Bengkel</h2>
            <p class="text-sm text-slate-500 font-medium">Sesuaikan identitas dan informasi kontak bengkel Anda.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-[18px] font-bold shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Bengkel -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Nama Bengkel</label>
                        <input type="text" name="nama_bengkel" value="{{ old('nama_bengkel', $setting->nama_bengkel ?? '') }}" required
                            class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                        @error('nama_bengkel') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Bengkel -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Email Bengkel</label>
                        <input type="email" name="email_bengkel" value="{{ old('email_bengkel', $setting->email_bengkel ?? '') }}"
                            class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">
                        @error('email_bengkel') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Nomor Telepon (WhatsApp)</label>
                        <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $setting->nomor_telepon ?? '') }}"
                            class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all"
                            placeholder="Contoh: 08123456789">
                        @error('nomor_telepon') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Logo -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Logo Bengkel</label>
                        <div class="flex items-center gap-4">
                            @if(isset($setting->logo_path))
                                <img src="{{ asset($setting->logo_path) }}" alt="Logo" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                            @endif
                            <input type="file" name="logo" 
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
                        </div>
                        @error('logo') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3"
                        class="w-full border-slate-200 rounded-[14px] px-4 py-3 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 transition-all">{{ old('alamat', $setting->alamat ?? '') }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-blue-950 text-white rounded-full font-black hover:bg-blue-900 transition-all shadow-lg hover:shadow-blue-900/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
