<x-sidebar-layout>
    <!-- <x-slot name="title">Riwayat Servis</x-slot> -->

    <div x-data="historyManager(@js($bookings))" x-effect="document.body.style.overflow = showModal ? 'hidden' : ''" class="space-y-8">
        
        <div class="-mt-6 sm:-mt-10 mb-2">
            <p class="text-slate-500 font-medium">Catatan medis perawatan motor Anda di MotoSkensa.</p>
        </div>

        <!-- Tab Filters -->
        <div class="flex border-b border-slate-200 gap-2 shrink-0 overflow-x-auto pb-px">
            <button @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'border-red-600 text-red-600 font-black' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 border-b-2 font-bold text-sm transition-all whitespace-nowrap">
                Semua
            </button>
            <button @click="activeTab = 'active'" 
                :class="activeTab === 'active' ? 'border-red-600 text-red-600 font-black' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 border-b-2 font-bold text-sm transition-all whitespace-nowrap flex items-center gap-1.5">
                Aktif / Proses
                <span class="px-2 py-0.5 text-xs bg-red-50 text-red-600 rounded-full font-black" x-text="activeCount"></span>
            </button>
            <button @click="activeTab = 'completed'" 
                :class="activeTab === 'completed' ? 'border-red-600 text-red-600 font-black' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 border-b-2 font-bold text-sm transition-all whitespace-nowrap flex items-center gap-1.5">
                Selesai
                <span class="px-2 py-0.5 text-xs bg-emerald-50 text-emerald-600 rounded-full font-black" x-text="completedCount"></span>
            </button>
            <button @click="activeTab = 'rejected'" 
                :class="activeTab === 'rejected' ? 'border-red-600 text-red-600 font-black' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 border-b-2 font-bold text-sm transition-all whitespace-nowrap flex items-center gap-1.5">
                Ditolak
                <span class="px-2 py-0.5 text-xs bg-red-50 text-red-600 rounded-full font-black" x-text="rejectedCount"></span>
            </button>
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
            <!-- Dynamic Empty State Helper -->
            <div x-show="(activeTab === 'all' && bookings.length === 0) ||
                         (activeTab === 'active' && activeCount === 0) ||
                         (activeTab === 'completed' && completedCount === 0) ||
                         (activeTab === 'rejected' && rejectedCount === 0)"
                 class="bg-white rounded-[32px] shadow-sm border border-slate-200 p-16 text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-blue-950 mb-2">Tidak Ada Data</h3>
                <p class="text-slate-500 font-medium max-w-sm mx-auto">Tidak ada catatan servis pada kategori ini.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach($bookings as $booking)
                    <div x-show="activeTab === 'all' || 
                                (activeTab === 'active' && ['pending', 'approved', 'in_progress'].includes('{{ $booking->status }}')) || 
                                (activeTab === 'completed' && '{{ $booking->status }}' === 'completed') || 
                                (activeTab === 'rejected' && '{{ $booking->status }}' === 'rejected')"
                         class="bg-white rounded-[24px] shadow-sm border border-slate-200 p-6 sm:p-8 hover:shadow-md transition-all group">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-6 pb-6 border-b border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0
                                    @if($booking->status === 'pending') bg-orange-50 text-orange-600
                                    @elseif($booking->status === 'approved') bg-blue-50 text-blue-600
                                    @elseif($booking->status === 'in_progress') bg-indigo-50 text-indigo-600
                                    @elseif($booking->status === 'completed') bg-emerald-50 text-emerald-600
                                    @else bg-red-50 text-red-600 @endif">
                                    @if($booking->status === 'completed')
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($booking->status === 'rejected')
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($booking->status === 'pending')
                                            <span class="px-2.5 py-1 bg-orange-50 text-orange-600 border border-orange-100 rounded-full text-[10px] font-black uppercase tracking-widest">Menunggu</span>
                                        @elseif($booking->status === 'approved')
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[10px] font-black uppercase tracking-widest">Disetujui</span>
                                        @elseif($booking->status === 'in_progress')
                                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-pulse"></span> Servis
                                            </span>
                                        @elseif($booking->status === 'completed')
                                            @if(($booking->payment_status ?? 'unpaid') === 'paid')
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Lunas</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Belum Bayar</span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-[10px] font-black uppercase tracking-widest">Ditolak</span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-400">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <h3 class="text-2xl font-black text-blue-950 group-hover:text-red-600 transition-colors">
                                        {{ $booking->kendaraan->merk }} {{ $booking->kendaraan->tipe }}
                                        <span class="text-lg font-bold text-slate-400 ml-1 uppercase">{{ $booking->kendaraan->plat_nomor }}</span>
                                    </h3>
                                </div>
                            </div>
                            @if($booking->nomor_invoice)
                            <div class="text-left sm:text-right w-full sm:w-auto bg-slate-50 sm:bg-transparent p-4 sm:p-0 rounded-xl">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Nomor</div>
                                <div class="font-mono font-bold text-blue-950 text-lg">{{ $booking->nomor_invoice }}</div>
                            </div>
                            @endif
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
                                        <span class="font-black text-blue-950">{{ $booking->mekanik->name ?? 'Belum Ditentukan' }}</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold text-slate-500">Keluhan:</span>
                                        <span class="font-bold text-blue-950 text-right max-w-[200px]" title="{{ $booking->keluhan }}">{{ $booking->keluhan ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 mt-4 border-t border-slate-200">
                                        <span class="font-black text-blue-950">
                                            @if($booking->status === 'completed') TOTAL BIAYA @else ESTIMASI BIAYA @endif
                                        </span>
                                        <span class="text-2xl font-black text-red-600">
                                            Rp @if($booking->status === 'completed') 
                                                {{ number_format($booking->total_harga, 0, ',', '.') }} 
                                            @else 
                                                {{ number_format($booking->paket_servis->sum('harga_jasa') + $booking->pemakaian_barang->sum(function($item) { return $item->pivot->is_approved ? ($item->harga_satuan * $item->pivot->jumlah) : 0; }), 0, ',', '.') }} 
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 justify-end pt-6 border-t border-slate-100">
                            <button @click="openModal(@js($booking))" class="flex items-center gap-2 px-6 py-3 bg-white text-blue-950 border border-slate-200 hover:border-blue-950 font-bold rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Detail Progres
                            </button>
                            @if($booking->status === 'completed')
                            <a href="{{ route('user.booking.invoice', $booking->id) }}" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-blue-950 text-white hover:bg-blue-900 font-bold rounded-xl transition-all shadow-lg shadow-blue-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Cetak Invoice
                            </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- PROGRESS MODAL (Reuse from Dashboard) -->
        <template x-teleport="body">
            <div x-cloak x-show="showModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <template x-if="showModal">
                        <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] my-8 relative">
                            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                                <div>
                                    <h3 class="text-lg font-black text-blue-950">Detail Servis <span x-text="'#' + activeBooking?.id"></span></h3>
                                    <p class="text-xs text-slate-500 font-medium">Plat Nomor: <span class="font-bold text-slate-700" x-text="activeBooking?.kendaraan?.plat_nomor"></span></p>
                                </div>
                                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 bg-white rounded-full p-2 shadow-sm border border-slate-100">&times;</button>
                            </div>
                            
                            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                                <!-- Dynamic Status Banner -->
                                 <div class="rounded-[16px] p-4 text-center font-bold text-sm uppercase tracking-widest border"
                                      :class="{
                                          'bg-orange-50 text-orange-700 border-orange-100': activeBooking?.status === 'pending',
                                          'bg-blue-50 text-blue-700 border-blue-100': activeBooking?.status === 'approved',
                                          'bg-indigo-50 text-indigo-700 border-indigo-100': activeBooking?.status === 'in_progress',
                                          'bg-green-50 text-green-700 border-green-100': activeBooking?.status === 'completed' && activeBooking?.payment_status === 'paid',
                                          'bg-rose-50 text-rose-700 border-rose-100': activeBooking?.status === 'completed' && activeBooking?.payment_status !== 'paid',
                                          'bg-red-50 text-red-700 border-red-100': activeBooking?.status === 'rejected',
                                      }"
                                      x-text="
                                          activeBooking?.status === 'pending' ? 'Menunggu Konfirmasi' :
                                          activeBooking?.status === 'approved' ? 'Disetujui / Menunggu Servis' :
                                          activeBooking?.status === 'in_progress' ? 'Sedang Dikerjakan' :
                                          activeBooking?.status === 'completed' ? (activeBooking?.payment_status === 'paid' ? 'Lunas' : 'Belum Bayar') : 'Ditolak'
                                      "></div>

                                <!-- Timeline Log -->
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
                                        <div x-show="!activeBooking?.progres || activeBooking?.progres.length === 0" class="text-sm text-slate-500 italic">Belum ada progres log.</div>
                                    </div>
                                </div>



                                <!-- Show Invoice summary if already completed or has active items -->
                                <div class="border-t border-slate-200 pt-6" x-show="activeBooking?.status === 'completed' || (activeBooking?.pemakaian_barang && activeBooking?.pemakaian_barang.length > 0)">
                                    <h4 class="text-sm font-black text-blue-950 mb-4 flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Rincian Biaya
                                        </span>
                                        <div class="flex gap-2">
                                            <template x-if="activeBooking?.payment_status === 'paid'">
                                                <span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">Lunas</span>
                                            </template>
                                            <template x-if="activeBooking?.payment_status !== 'paid' && activeBooking?.status === 'completed'">
                                                <span class="text-xs font-bold text-rose-700 bg-rose-100 px-3 py-1 rounded-full">Belum Bayar</span>
                                            </template>
                                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full" x-show="activeBooking?.nomor_invoice" x-text="activeBooking?.nomor_invoice"></span>
                                        </div>
                                    </h4>
                                    
                                    <div class="bg-slate-50 rounded-[16px] p-5 border border-slate-200 mb-4">
                                        <div class="mb-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1">Jasa & Servis</p>
                                            <template x-for="paket in activeBooking?.paket_servis" :key="paket.id">
                                                <div class="flex justify-between text-sm mb-1">
                                                    <span class="font-medium text-blue-950" x-text="paket.nama_paket"></span>
                                                    <span class="font-bold text-slate-700" x-text="'Rp ' + parseInt(paket.harga_jasa).toLocaleString('id-ID')"></span>
                                                </div>
                                            </template>
                                            <div x-show="!activeBooking?.paket_servis || activeBooking?.paket_servis.length === 0" class="text-sm text-slate-500 italic">Servis Umum</div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1">Sparepart & Oli</p>
                                            <template x-for="item in activeBooking?.pemakaian_barang" :key="item.id">
                                                <div class="flex justify-between text-sm mb-1" x-show="item.pivot.is_approved == 1 || item.pivot.is_approved == true">
                                                    <span class="font-medium text-blue-950">
                                                        <span x-text="item.nama_barang"></span> 
                                                        <span class="text-xs text-slate-500 ml-1" x-text="'x' + item.pivot.jumlah"></span>
                                                    </span>
                                                    <span class="font-bold text-slate-700" x-text="'Rp ' + (parseInt(item.harga_satuan) * parseInt(item.pivot.jumlah)).toLocaleString('id-ID')"></span>
                                                </div>
                                            </template>
                                            <div x-show="!activeBooking?.pemakaian_barang || activeBooking?.pemakaian_barang.length === 0" class="text-sm text-slate-500 italic">Tidak ada sparepart tambahan.</div>
                                        </div>
                                        <div class="border-t border-slate-300 pt-3 mt-2 flex justify-between items-center">
                                             <span class="font-black text-blue-950">ESTIMASI / TOTAL BIAYA</span>
                                             <span class="text-xl font-black text-red-600" x-text="'Rp ' + parseInt(calculateModalTotal).toLocaleString('id-ID')"></span>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('historyManager', (initialBookings) => ({
                    showModal: false,
                    activeBooking: null,
                    activeTab: 'all',
                    bookings: initialBookings,
                    get activeCount() {
                        return this.bookings.filter(b => ['pending', 'approved', 'in_progress'].includes(b.status)).length;
                    },
                    get completedCount() {
                        return this.bookings.filter(b => b.status === 'completed').length;
                    },
                    get rejectedCount() {
                        return this.bookings.filter(b => b.status === 'rejected').length;
                    },
                    openModal(b) {
                        this.activeBooking = b;
                        this.showModal = true;
                    },
                    get calculateModalTotal() {
                        if (!this.activeBooking) return 0;
                        if (this.activeBooking.status === 'completed') {
                            return parseInt(this.activeBooking.total_harga || 0);
                        }
                        let totalJasa = this.activeBooking.paket_servis ? this.activeBooking.paket_servis.reduce((sum, p) => sum + parseInt(p.harga_jasa || 0), 0) : 0;
                        let totalSparepart = 0;
                        if (this.activeBooking.pemakaian_barang) {
                            this.activeBooking.pemakaian_barang.forEach(item => {
                                if (item.pivot && (item.pivot.is_approved == 1 || item.pivot.is_approved == true)) {
                                    totalSparepart += parseInt(item.harga_satuan || 0) * parseInt(item.pivot.jumlah || 0);
                                }
                            });
                        }
                        return totalJasa + totalSparepart;
                    }
                }));
            });
        </script>
    </x-slot>
</x-sidebar-layout>
