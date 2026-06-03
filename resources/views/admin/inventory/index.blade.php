<x-sidebar-layout>
    <x-slot name="title">Inventaris Stok Barang</x-slot>

    <div x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            editData: { id: '', nama_barang: '', satuan: '', stok: '', harga_satuan: '' },
            openEditModal(item) {
                this.editData = item;
                this.showEditModal = true;
            }
        }" x-effect="(showCreateModal || showEditModal) ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')" class="space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-blue-950">Daftar Barang & Sparepart</h2>
                <p class="text-sm text-slate-500 font-medium">Kelola stok oli, busi, dan suku cadang lainnya.</p>
            </div>
            @if(auth()->user()->role === 'admin')
            <button @click="showCreateModal = true" class="px-5 py-2.5 bg-red-600 text-white rounded-full text-sm font-bold shadow-md hover:bg-red-700/90 transition-all">
                + Tambah Barang
            </button>
            @endif
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-sm">
                            <th class="py-4 px-6 font-bold text-slate-500">Nama Barang</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Satuan</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Stok Tersedia</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Harga Satuan</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($items as $item)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-blue-950">{{ $item->nama_barang }}</td>
                            <td class="py-4 px-6 text-slate-500">{{ $item->satuan }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->stok < 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $item->stok }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-700 font-medium">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                @if(auth()->user()->role === 'admin')
                                    <button @click="openEditModal({{ $item->toJson() }})" class="text-blue-600 hover:text-blue-800 font-bold px-2 py-1 bg-blue-50 hover:bg-blue-100 rounded transition-colors">Edit</button>
                                    <form action="{{ route('admin.inventory.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition-colors">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Hanya lihat</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 font-medium">Belum ada data barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

        <!-- CREATE MODAL -->
        <template x-teleport="body">
            <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <div @click.away="showCreateModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-lg font-black text-blue-950">Tambah Barang Baru</h3>
                            <button @click="showCreateModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                        </div>
                        <form action="{{ route('admin.inventory.store') }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Barang</label>
                                <input type="text" name="nama_barang" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Satuan (Pcs/Botol/Set)</label>
                                <input type="text" name="satuan" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Stok Awal</label>
                                    <input type="number" name="stok" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Harga (Rp)</label>
                                    <input type="number" name="harga_satuan" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg">Simpan Barang</button>
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
                    <div @click.away="showEditModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-lg font-black text-blue-950">Edit Barang</h3>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                        </div>
                        <form :action="'/admin/inventory/' + editData.id" method="POST" class="p-6 space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Barang</label>
                                <input type="text" name="nama_barang" x-model="editData.nama_barang" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Satuan</label>
                                <input type="text" name="satuan" x-model="editData.satuan" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Stok</label>
                                    <input type="number" name="stok" x-model="editData.stok" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Harga (Rp)</label>
                                    <input type="number" name="harga_satuan" x-model="editData.harga_satuan" min="0" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg">Update Barang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-sidebar-layout>
