<x-sidebar-layout>
    <x-slot name="title">Jadwal Operasional Harian</x-slot>

    <div x-data="{ showCreateModal: false }" x-effect="showCreateModal ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')" class="space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-blue-950">Pengaturan Jadwal & Kapasitas</h2>
                <p class="text-sm text-slate-500 font-medium">Buka jadwal per hari dan tentukan kuota maksimal menit pengerjaan.</p>
            </div>
            @if(auth()->user()->role === 'admin')
            <button @click="showCreateModal = true" class="px-5 py-2.5 bg-red-600 text-white rounded-full text-sm font-bold shadow-md hover:bg-red-700/90 transition-all">
                + Set Jadwal Baru
            </button>
            @endif
        </div>

        <!-- Data Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($schedules as $sch)
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col relative">
                <div class="p-6 pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($sch->tanggal)->translatedFormat('l') }}</p>
                            <h3 class="text-2xl font-black text-blue-950">{{ \Carbon\Carbon::parse($sch->tanggal)->format('d M Y') }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold whitespace-nowrap mb-1">
                                Dibuka
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-500">Jam Operasional</span>
                            <span class="font-bold text-blue-950">{{ substr($sch->jam_buka, 0, 5) }} - {{ substr($sch->jam_tutup, 0, 5) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-500">Kuota Terpakai</span>
                            <span class="font-bold {{ $sch->terpakai_menit >= $sch->kapasitas_menit ? 'text-red-600' : 'text-blue-950' }}">
                                {{ $sch->terpakai_menit }} / {{ $sch->kapasitas_menit }} Menit
                            </span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 rounded-full h-2.5 mt-2">
                            @php
                                $percentage = ($sch->kapasitas_menit > 0) ? ($sch->terpakai_menit / $sch->kapasitas_menit) * 100 : 0;
                                $color = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-orange-500' : 'bg-green-500');
                            @endphp
                            <div class="{{ $color }} h-2.5 rounded-full transition-all" style="width: {{ min(100, $percentage) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-50 px-6 py-4 flex justify-between items-center border-t border-slate-100 mt-auto">
                    <span class="text-xs font-bold text-slate-500">ID: #{{ $sch->id }}</span>
                    @if(auth()->user()->role === 'admin')
                    <form action="{{ route('admin.schedules.destroy', $sch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Menghapus jadwal bisa mempengaruhi booking yang sudah masuk pada tanggal ini. Lanjutkan?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-sm">Hapus Jadwal</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 text-center py-10 bg-white rounded-[24px] border border-slate-200">
                <p class="text-slate-500 font-medium">Belum ada jadwal harian yang diset.</p>
            </div>
            @endforelse
        </div>

        @if($schedules->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        @endif

        <!-- CREATE MODAL -->
        <template x-teleport="body">
            <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <div @click.away="showCreateModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-lg font-black text-blue-950">Set Jadwal Baru</h3>
                            <button @click="showCreateModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                        </div>
                        <form action="{{ route('admin.schedules.store') }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal</label>
                                <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Buka</label>
                                    <input type="time" name="jam_buka" value="08:00" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Tutup</label>
                                    <input type="time" name="jam_tutup" value="16:00" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kapasitas Maksimal (Menit)</label>
                                <input type="number" name="kapasitas_menit" min="60" value="480" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50">
                                <p class="text-xs text-slate-400 mt-1">Misal: 480 menit (8 Jam operasional dengan 1 mekanik)</p>
                            </div>
                            <div class="pt-4 flex justify-end gap-2">
                                <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg">Simpan Jadwal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-sidebar-layout>
