<x-sidebar-layout>
    <x-slot name="title">Manajemen Paket Servis</x-slot>

    <div x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            editData: { id: '', nama_paket: '', tipe: 'dengan_part', deskripsi: '', estimasi_menit: '', harga_jasa: '' },
            openEditModal(item) {
                this.editData = item;
                this.showEditModal = true;
            }
        }" x-effect="(showCreateModal || showEditModal) ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')" class="space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-blue-950">Daftar Paket Servis</h2>
                <p class="text-sm text-slate-500 font-medium">Kelola layanan servis, durasi pengerjaan, dan biaya jasa mekanik.</p>
            </div>
            @if(auth()->user()->role === 'admin')
            <button @click="showCreateModal = true" class="px-5 py-2.5 bg-red-600 text-white rounded-full text-sm font-bold shadow-md hover:bg-red-700/90 transition-all">
                + Tambah Paket
            </button>
            @endif
        </div>

        <!-- Data Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($packages as $pkg)
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col relative group hover:border-red-300 transition-colors">
                <!-- Service Image Preview -->
                <div class="h-40 bg-slate-100 overflow-hidden relative">
                    <img src="{{ $pkg->image_path ? asset($pkg->image_path) : 'https://images.unsplash.com/photo-1558981403-c5f91dbcf9ad?q=80&w=800&auto=format&fit=crop' }}" 
                         alt="{{ $pkg->nama_paket }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/40 to-transparent"></div>
                </div>

                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-black text-blue-950 pr-4">{{ $pkg->nama_paket }}</h3>
                            <span class="text-[10px] font-black {{ $pkg->tipe === 'jasa_saja' ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-widest">
                                {{ $pkg->tipe === 'jasa_saja' ? 'Hanya Jasa (Flat)' : 'Jasa + Suku Cadang' }}
                            </span>
                        </div>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold whitespace-nowrap">
                            {{ $pkg->estimasi_menit }} Menit
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 font-medium mb-4 line-clamp-3">{{ $pkg->deskripsi }}</p>
                    <p class="text-xl font-black text-red-600">Rp {{ number_format($pkg->harga_jasa, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white-50 px-6 py-4 flex justify-between items-center border-t border-slate-100">
                    <!-- <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Servis </span>
                    </div> -->
                    <div class="flex gap-2">
                        @if(auth()->user()->role === 'admin')
                            <button @click="openEditModal({{ $pkg->toJson() }})" class="text-blue-600 hover:text-blue-800 font-bold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors text-sm">Edit</button>
                            <form action="{{ route('admin.services.destroy', $pkg->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus paket ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-sm">Hapus</button>
                            </form>
                        @else
                            <span class="text-xs text-slate-400 italic">Hanya lihat</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="lg:col-span-2 text-center py-10 bg-white rounded-[24px] border border-slate-200">
                <p class="text-slate-500 font-medium">Belum ada paket servis.</p>
            </div>
            @endforelse
        </div>
        
        @if($packages->hasPages())
            <div class="p-4">
                {{ $packages->links() }}
            </div>
        @endif

        <!-- CREATE MODAL -->
        <template x-teleport="body">
            <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <div @click.away="showCreateModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-lg font-black text-blue-950">Tambah Paket Servis</h3>
                            <button @click="showCreateModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                        </div>
                        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Foto Paket</label>
                                    <input type="file" name="image" accept="image/*" class="w-full border-slate-200 rounded-[14px] px-4 py-2 text-xs text-blue-950 bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tipe Layanan</label>
                                    <select name="tipe" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 font-medium bg-slate-50 focus:ring-red-500">
                                        <option value="dengan_part">Jasa + Suku Cadang</option>
                                        <option value="jasa_saja">Hanya Jasa (Flat)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Paket</label>
                                <input type="text" name="nama_paket" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50" placeholder="Contoh: Servis CVT">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Deskripsi Singkat</label>
                                <textarea name="deskripsi" rows="3" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Estimasi Waktu (Menit)</label>
                                    <input type="number" name="estimasi_menit" min="1" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Harga Jasa (Rp)</label>
                                    <input type="number" name="harga_jasa" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg">Simpan Paket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- EDIT MODAL -->
        <template x-teleport="body">
            <div x-cloak x-show="showEditModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <div @click.away="showEditModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-lg font-black text-blue-950">Edit Paket Servis</h3>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                        </div>
                        <form :action="'/admin/services/' + editData.id" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Foto Paket</label>
                                    <input type="file" name="image" accept="image/*" class="w-full border-slate-200 rounded-[14px] px-4 py-2 text-xs text-blue-950 bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tipe Layanan</label>
                                    <select name="tipe" x-model="editData.tipe" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 font-medium bg-slate-50 focus:ring-red-500">
                                        <option value="dengan_part">Jasa + Suku Cadang</option>
                                        <option value="jasa_saja">Hanya Jasa (Flat)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Paket</label>
                                <input type="text" name="nama_paket" x-model="editData.nama_paket" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Deskripsi Singkat</label>
                                <textarea name="deskripsi" x-model="editData.deskripsi" rows="3" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Estimasi Waktu (Menit)</label>
                                    <input type="number" name="estimasi_menit" x-model="editData.estimasi_menit" min="1" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Harga Jasa (Rp)</label>
                                    <input type="number" name="harga_jasa" x-model="editData.harga_jasa" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg">Update Paket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-sidebar-layout>
