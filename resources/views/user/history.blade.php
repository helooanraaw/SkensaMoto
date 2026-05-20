<x-sidebar-layout>
    <!-- <x-slot name="title">Riwayat Servis</x-slot> -->

    <div x-data="{ 
            showModal: false, 
            activeBooking: null,
            openModal(b) {
                this.activeBooking = b;
                this.showModal = true;
            }
        }" class="space-y-8">
        
        <div class="-mt-6 sm:-mt-10 mb-2">
            <p class="text-slate-500 font-medium">Catatan medis perawatan motor Anda di MotoSkensa.</p>
        </div>

        @if($bookings->isEmpty())
            <div class="bg-white rounded-[32px] shadow-sm border border-slate-200 p-16 text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-blue-950 mb-2">Belum Ada Riwayat</h3>
                <p class="text-slate-500 font-medium max-w-sm mx-auto">Motor Anda belum memiliki catatan servis yang telah selesai di bengkel kami.</p>
                <a href="{{ route('user.dashboard') }}" class="mt-8 inline-block bg-red-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                    Mulai Booking Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 p-6 sm:p-8 hover:shadow-md transition-all group">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-6 pb-6 border-b border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-green-600 bg-green-100 px-2.5 py-1 rounded-full uppercase tracking-wider">Selesai</span>
                                        <span class="text-sm font-bold text-slate-400">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <h3 class="text-2xl font-black text-blue-950 group-hover:text-red-600 transition-colors">
                                        {{ $booking->kendaraan->merk }} {{ $booking->kendaraan->tipe }}
                                        <span class="text-lg font-bold text-slate-400 ml-1 uppercase">{{ $booking->kendaraan->plat_nomor }}</span>
                                    </h3>
                                </div>
                            </div>
                            <div class="text-left sm:text-right w-full sm:w-auto bg-slate-50 sm:bg-transparent p-4 sm:p-0 rounded-xl">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Nomor Invoice</div>
                                <div class="font-mono font-bold text-blue-950 text-lg">{{ $booking->nomor_invoice }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            <div>
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Layanan & Jasa</h4>
                                <div class="space-y-3">
                                    @forelse($booking->paket_servis as $paket)
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 hover:border-red-200 transition-colors">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                                                    <div class="font-bold text-sm text-blue-950">{{ $paket->nama_paket }}</div>
                                                </div>
                                                <div class="text-xs font-black text-red-600">Rp {{ number_format($paket->harga_jasa, 0, ',', '.') }}</div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">{{ $paket->deskripsi }}</p>
                                        </div>
                                    @empty
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                                            <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                                            <div class="text-sm text-slate-500 font-medium italic">Servis Umum / Perbaikan Keluhan</div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="bg-blue-950/5 p-6 rounded-[20px] border border-blue-950/5">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Ringkasan Biaya</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-500">Mekanik:</span>
                                        <span class="font-black text-blue-950">{{ $booking->mekanik->name ?? 'Mekanik MotoSkensa' }}</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold text-slate-500">Keluhan:</span>
                                        <span class="font-bold text-blue-950 text-right max-w-[200px]" title="{{ $booking->keluhan }}">{{ $booking->keluhan ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 mt-4 border-t border-slate-200">
                                        <span class="font-black text-blue-950">TOTAL BIAYA</span>
                                        <span class="text-2xl font-black text-red-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 justify-end pt-6 border-t border-slate-100">
                            <button @click="openModal({{ $booking->load(['kendaraan', 'paket_servis', 'mekanik', 'progres', 'pemakaian_barang'])->toJson() }})" class="flex items-center gap-2 px-6 py-3 bg-white text-blue-950 border border-slate-200 hover:border-blue-950 font-bold rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Detail Progres
                            </button>
                            <a href="{{ route('user.booking.invoice', $booking->id) }}" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-blue-950 text-white hover:bg-blue-900 font-bold rounded-xl transition-all shadow-lg shadow-blue-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Cetak Invoice
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- PROGRESS MODAL (Reuse from Dashboard) -->
        <div x-cloak x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
            <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-8 flex flex-col max-h-[90vh]">
                
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-blue-950">Detail Servis <span x-text="'#' + activeBooking?.id"></span></h3>
                        <p class="text-xs text-slate-500 font-medium">Plat Nomor: <span class="font-bold text-slate-700" x-text="activeBooking?.kendaraan?.plat_nomor"></span></p>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-red-500 bg-white rounded-full p-2 shadow-sm border border-slate-100">&times;</button>
                </div>

                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <div class="rounded-[16px] p-4 text-center font-bold text-sm bg-green-50 text-green-700">
                        Status: <span class="uppercase tracking-widest">SELESAI</span>
                    </div>

                    <div>
                        <h4 class="text-sm font-black text-blue-950 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Timeline Progres Servis
                        </h4>
                        
                        <div class="relative border-l-2 border-slate-200 ml-3 space-y-6 pb-2">
                            <template x-for="log in activeBooking?.progres" :key="log.id">
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 bg-white border-2 border-red-500 rounded-full"></div>
                                    <p class="text-xs text-slate-400 font-bold mb-0.5" x-text="new Date(log.created_at).toLocaleString('id-ID')"></p>
                                    <p class="text-sm font-medium text-slate-700" x-text="log.status_log"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <h4 class="text-sm font-black text-blue-950 mb-4 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Rincian Invoice Akhir
                            </span>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full" x-text="activeBooking?.nomor_invoice"></span>
                        </h4>
                        
                        <div class="bg-slate-50 rounded-[16px] p-5 border border-slate-200">
                            <div class="mb-4">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1">Jasa & Servis</p>
                                <template x-for="paket in activeBooking?.paket_servis" :key="paket.id">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-blue-950" x-text="paket.nama_paket"></span>
                                        <span class="font-bold text-slate-700" x-text="'Rp ' + parseInt(paket.harga_jasa).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="mb-4">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1">Sparepart & Oli</p>
                                <template x-for="item in activeBooking?.pemakaian_barang" :key="item.id">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-blue-950">
                                            <span x-text="item.nama_barang"></span> 
                                            <span class="text-xs text-slate-500 ml-1" x-text="'x' + item.pivot.jumlah"></span>
                                        </span>
                                        <span class="font-bold text-slate-700" x-text="'Rp ' + (parseInt(item.harga_satuan) * parseInt(item.pivot.jumlah)).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                                <div x-show="!activeBooking?.pemakaian_barang || activeBooking?.pemakaian_barang.length === 0" class="text-sm text-slate-500 italic">Tidak ada penggantian sparepart.</div>
                            </div>

                            <div class="border-t border-slate-300 pt-3 mt-2 flex justify-between items-center">
                                <span class="font-black text-blue-950">TOTAL BIAYA</span>
                                <span class="text-xl font-black text-red-600" x-text="'Rp ' + parseInt(activeBooking?.total_harga || 0).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
