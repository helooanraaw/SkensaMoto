<x-sidebar-layout>
    <x-slot name="title">Antrean Servis</x-slot>

    <div x-data="{ 
            showApproveModal: false,
            showQuotationModal: false,
            showDetailModal: false,
            activeBooking: null,
            spareparts: [],
            
            openApprove(booking) {
                this.activeBooking = booking;
                this.showApproveModal = true;
            },
            
            openQuotation(booking) {
                this.activeBooking = booking;
                this.spareparts = []; // reset
                this.showQuotationModal = true;
            },

            openDetail(booking) {
                this.activeBooking = booking;
                this.showDetailModal = true;
            },

            addSparepart() {
                this.spareparts.push({ id: '', qty: 1 });
            },

            removeSparepart(index) {
                this.spareparts.splice(index, 1);
            }
        }" class="space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-blue-950">Daftar Antrean Servis</h2>
                <p class="text-sm text-slate-500 font-medium">Kelola status, jadwal, dan penyelesaian servis motor.</p>
            </div>
            <div class="flex gap-2">
                <a href="?status=all" class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ request('status', 'all') == 'all' ? 'bg-blue-950 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Semua</a>
                <a href="?status=pending" class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ request('status') == 'pending' ? 'bg-orange-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Pending</a>
                <a href="?status=in_progress" class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ request('status') == 'in_progress' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Dikerjakan</a>
                <a href="?status=completed" class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ request('status') == 'completed' ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Selesai</a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-sm">
                            <th class="py-4 px-6 font-bold text-slate-500">ID / Waktu</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Pelanggan</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Kendaraan</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Layanan & Keluhan</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Status</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($bookings as $booking)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-black text-blue-950 block">#{{ $booking->id }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-4 px-6 font-bold text-blue-950">{{ $booking->user->name }}</td>
                            <td class="py-4 px-6">
                                <span class="block font-bold text-slate-700">{{ $booking->kendaraan->merek }} {{ $booking->kendaraan->tipe }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->kendaraan->plat_nomor }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="block font-bold text-slate-700">
                                    @if($booking->paket_servis->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-1">
                                            @foreach($booking->paket_servis as $paket)
                                                <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded uppercase tracking-wider border border-blue-100">
                                                    {{ $paket->nama_paket }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded uppercase tracking-wider border border-slate-200">Servis Umum</span>
                                    @endif
                                </span>
                                <span class="text-xs text-slate-500 truncate max-w-xs block">{{ $booking->keluhan }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-100 text-orange-700',
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-slate-100 text-slate-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved' => 'Disetujui',
                                        'in_progress' => 'Dikerjakan',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$booking->status] }}">
                                    {{ $statusLabels[$booking->status] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button @click="openDetail({{ $booking->toJson() }})" class="text-slate-600 hover:text-slate-900 font-bold px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded transition-colors mr-1">Detail</button>

                                @if($booking->status == 'pending')
                                    @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                        <button @click="openApprove({{ $booking->toJson() }})" class="text-emerald-600 hover:text-emerald-800 font-bold px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition-colors">Terima</button>
                                        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition-colors">Tolak</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-orange-600 font-bold italic">Menunggu Persetujuan</span>
                                    @endif
                                @elseif($booking->status == 'approved')
                                    <form action="{{ route('admin.bookings.start', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Mulai kerjakan motor ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded transition-colors">Mulai Servis</button>
                                    </form>
                                @elseif($booking->status == 'in_progress')
                                    @php
                                        $needsQuotation = false;
                                        foreach($booking->paket_servis as $paket) {
                                            if(str_contains(strtolower($paket->nama_paket), 'cek') || str_contains(strtolower($paket->nama_paket), 'kerusakan')) {
                                                $needsQuotation = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if(is_null($booking->quotation_status))
                                        @if($needsQuotation)
                                            <button @click="openQuotation({{ $booking->toJson() }})" class="text-indigo-600 hover:text-indigo-800 font-bold px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 rounded transition-colors">Kirim Estimasi</button>
                                        @else
                                            <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Selesaikan servis ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-bold px-3 py-1.5 bg-green-50 hover:bg-green-100 rounded transition-colors">Selesaikan Servis</button>
                                            </form>
                                        @endif
                                    @elseif($booking->quotation_status == 'sent')
                                        <span class="text-xs text-orange-600 font-bold bg-orange-50 px-3 py-1.5 rounded inline-block">Menunggu Persetujuan Pelanggan</span>
                                    @elseif($booking->quotation_status == 'approved')
                                        <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Selesaikan servis dengan sparepart yang disetujui?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-bold px-3 py-1.5 bg-green-50 hover:bg-green-100 rounded transition-colors">Selesaikan Servis</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 font-bold italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 font-medium">Belum ada data antrean.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

        <!-- APPROVE MODAL -->
        <div x-cloak x-show="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.away="showApproveModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-black text-blue-950">Terima Booking <span x-text="activeBooking?.id"></span></h3>
                    <button @click="showApproveModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                </div>
                <form :action="'/admin/bookings/' + activeBooking?.id + '/approve'" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <p class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Layanan yang Diminta</p>
                        <div class="flex flex-wrap gap-2 mb-2" x-show="activeBooking?.paket_servis?.length > 0">
                            <template x-for="paket in activeBooking?.paket_servis">
                                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100" x-text="paket.nama_paket + ' (' + paket.estimasi_menit + ' mnt)'"></span>
                            </template>
                        </div>
                        <p x-show="!activeBooking?.paket_servis || activeBooking?.paket_servis?.length === 0" class="text-sm font-medium text-slate-500 italic">Hanya Servis Umum / Pengecekan</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal Permintaan Servis</label>
                        <div class="w-full border border-slate-200 rounded-[14px] px-4 py-3 bg-slate-100 text-blue-950 font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span x-text="activeBooking?.tanggal_formatted || activeBooking?.tanggal"></span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">*Sistem akan otomatis menyesuaikan jadwal dengan tanggal ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Estimasi Waktu Pengerjaan (Menit)</label>
                        <input type="number" name="estimasi_total_menit" min="1" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50" placeholder="Misal: 60" :value="activeBooking?.paket_servis?.length > 0 ? activeBooking.paket_servis.reduce((sum, p) => sum + p.estimasi_menit, 0) : ''">
                        <p x-show="activeBooking?.paket_servis?.length > 0" class="text-xs text-emerald-600 mt-1 font-bold">Otomatis dihitung dari pilihan paket pelanggan.</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button type="button" @click="showApproveModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white rounded-full font-bold hover:bg-emerald-700 transition-colors shadow-lg">Setujui Booking</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- QUOTATION MODAL -->
        <div x-cloak x-show="showQuotationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
            <div @click.away="showQuotationModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all my-8">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-black text-blue-950">Kirim Estimasi Biaya <span x-text="activeBooking?.id"></span></h3>
                    <button @click="showQuotationModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                </div>
                <form :action="'/admin/bookings/' + activeBooking?.id + '/send-quotation'" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <div class="bg-blue-50 text-blue-800 p-4 rounded-[14px] text-sm font-medium">
                        Berikan catatan hasil diagnosis kerusakan dan rekomendasi sparepart. Pelanggan bisa mereview biayanya sebelum servis dieksekusi final.
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Catatan Kerusakan (Diagnosis)</label>
                        <textarea name="catatan_kerusakan" rows="3" required class="w-full border-slate-200 rounded-[14px] px-4 py-2.5 text-blue-950 focus:ring-red-500 focus:border-red-500 font-medium bg-slate-50" placeholder="Misal: Kampas rem aus, perlu ganti oli mesin..."></textarea>
                    </div>

                    <div class="space-y-3 border border-slate-200 rounded-[14px] p-4 bg-slate-50">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Rekomendasi Sparepart</label>
                            <button type="button" @click="addSparepart" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-100 px-2 py-1 rounded">+ Tambah</button>
                        </div>

                        <template x-for="(item, index) in spareparts" :key="index">
                            <div class="flex gap-2 items-start">
                                <div class="flex-1">
                                    <select :name="'barang_id[]'" required class="w-full border-slate-200 rounded-[10px] px-3 py-2 text-blue-950 focus:ring-red-500 focus:border-red-500 text-sm font-medium bg-white">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($inventories as $inv)
                                            <option value="{{ $inv->id }}">{{ $inv->nama_barang }} (Rp {{ number_format($inv->harga_satuan,0,',','.') }} | Stok: {{ $inv->stok }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <input type="number" :name="'jumlah[]'" x-model="item.qty" min="1" required class="w-full border-slate-200 rounded-[10px] px-3 py-2 text-blue-950 focus:ring-red-500 focus:border-red-500 text-sm font-medium bg-white" placeholder="Qty">
                                </div>
                                <button type="button" @click="removeSparepart(index)" class="mt-2 text-slate-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                        <p x-show="spareparts.length === 0" class="text-xs text-slate-500 italic text-center py-2">Hanya perbaikan, tidak ada ganti sparepart.</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button type="button" @click="showQuotationModal = false" class="px-5 py-2.5 rounded-full font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-full font-bold hover:bg-indigo-700 transition-colors shadow-lg">Kirim Estimasi ke Pelanggan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DETAIL MODAL (Admin) -->
        <div x-cloak x-show="showDetailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
            <div @click.away="showDetailModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-8 flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-blue-950">Info Servis <span x-text="'#' + activeBooking?.id"></span></h3>
                        <p class="text-xs font-bold text-slate-500" x-text="activeBooking?.status"></p>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-red-500">&times;</button>
                </div>
                
                <div class="p-6 overflow-y-auto space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase">Pelanggan</p>
                            <p class="font-black text-blue-950 mt-1" x-text="activeBooking?.user?.name"></p>
                            <p class="text-sm font-medium text-slate-600" x-text="activeBooking?.user?.nomor_telepon || 'Tidak ada nomor telepon'"></p>
                            <a :href="'https://wa.me/' + (activeBooking?.user?.nomor_telepon?.startsWith('0') ? '62' + activeBooking?.user?.nomor_telepon.substring(1) : activeBooking?.user?.nomor_telepon) + '?text=Halo%20' + activeBooking?.user?.name + ',%20ini%20dari%20Bengkel%20SkensaMotoHub...'" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825.001 6.938 3.113 6.938 6.937-.001 3.824-3.113 6.936-6.938 6.936z"/></svg>
                                Hubungi via WhatsApp
                            </a>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase">Kendaraan</p>
                            <p class="font-black text-blue-950 mt-1" x-text="activeBooking?.kendaraan?.plat_nomor"></p>
                            <p class="text-sm font-medium text-slate-600"><span x-text="activeBooking?.kendaraan?.merk"></span> <span x-text="activeBooking?.kendaraan?.tipe"></span></p>
                            
                            <template x-if="activeBooking?.nomor_invoice">
                                <div class="mt-3 pt-3 border-t border-slate-200">
                                    <p class="text-xs font-bold text-slate-400 uppercase">Nomor</p>
                                    <p class="text-sm font-black text-indigo-600" x-text="activeBooking?.nomor_invoice"></p>
                                </div>
                            </template>
                            
                            <template x-if="activeBooking?.mekanik">
                                <div class="mt-3 pt-3 border-t border-slate-200">
                                    <p class="text-xs font-bold text-slate-400 uppercase">Mekanik</p>
                                    <p class="text-sm font-black text-blue-950 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span x-text="activeBooking?.mekanik?.name"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Layanan yang Dipilih Pelanggan</p>
                        <div class="flex flex-wrap gap-2 mb-4" x-show="activeBooking?.paket_servis?.length > 0">
                            <template x-for="paket in activeBooking?.paket_servis">
                                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100" x-text="paket.nama_paket"></span>
                            </template>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Keluhan Pelanggan</p>
                        <p class="text-sm font-medium text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200" x-text="activeBooking?.keluhan"></p>
                    </div>

                    <div x-show="activeBooking?.catatan_kerusakan">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Catatan Kerusakan (Diagnosis Admin)</p>
                        <p class="text-sm font-medium text-red-700 bg-red-50 p-3 rounded-xl border border-red-200" x-text="activeBooking?.catatan_kerusakan"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-sidebar-layout>
