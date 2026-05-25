<x-sidebar-layout>
    <x-slot name="title">Dashboard Pelanggan</x-slot>

    <div x-data="dashboardManager" x-effect="document.body.style.overflow = (showModal || showEditModal) ? 'hidden' : ''" class="space-y-8">

        <!-- Welcome Banner -->
        <div class="bg-blue-950 p-8 sm:p-10 rounded-[32px] text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Halo, {{ auth()->user()->name }}!</h2>
                <p class="text-blue-300 font-thin max-w-xl leading-relaxed">Selamat datang di SkensaMoto. Anda dapat mendaftarkan kendaraan, melihat jadwal booking, dan melacak proses servis motor Anda secara real-time.</p>
            </div>
        </div>

        @php
            $actionNeeded = \App\Models\Booking::where('user_id', auth()->id())
                ->where('status', 'in_progress')
                ->where('quotation_status', 'sent')
                ->with(['kendaraan', 'paket_servis', 'mekanik', 'progres', 'pemakaian_barang'])
                ->first();
        @endphp

        @if($actionNeeded)
        <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-[24px] shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-orange-800">Menunggu Persetujuan Anda</h3>
                <p class="text-sm text-orange-700 font-medium mt-1">Mekanik telah selesai melakukan estimasi biaya untuk <span class="font-bold">{{ $actionNeeded->kendaraan->plat_nomor }}</span>. Silakan periksa rincian biaya dan berikan persetujuan agar servis dapat segera dimulai.</p>
            </div>
            <button @click="openModal(@js($actionNeeded))" class="px-5 py-2.5 bg-orange-500 text-white rounded-full text-sm font-bold shadow hover:bg-orange-600 transition-all ml-4 shrink-0">
                Lihat Detail
            </button>
        </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Kendaraan & Bookings -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Data Kendaraan Section -->
                <div id="garasi-section" class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden" x-data="{ openForm: false }">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-black text-blue-950 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM9 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17V12a2 2 0 0 0-2-2h-3L8 7H5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10 7 13M12 17h5"/></svg>
                            Garasi Kendaraan
                        </h3>
                        <button @click="openForm = !openForm" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition">
                            + Tambah Motor
                        </button>
                    </div>

                    <!-- Form Tambah -->
                    <div x-cloak x-show="openForm" class="p-6 border-b border-slate-100 bg-red-50/30">
                        <form action="{{ route('user.kendaraan.store') }}" method="POST" class="grid grid-cols-2 gap-4">
                            @csrf
                            <div class="col-span-2">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Plat Nomor</label>
                                <input type="text" name="plat_nomor" required placeholder="DK 1234 ABC" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm uppercase focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tipe Motor (Honda)</label>
                                <input type="text" name="tipe" required placeholder="Contoh: BeAT, Vario, Scoopy" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tahun Pembuatan</label>
                                <input type="number" name="tahun" required min="1990" max="{{ date('Y') + 1 }}" placeholder="Contoh: 2022" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            <input type="hidden" name="merk" value="Honda">
                            <button type="submit" class="col-span-2 py-3 bg-blue-950 text-white rounded-xl font-bold hover:bg-blue-900 transition-colors shadow">Simpan Motor</button>
                        </form>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($kendaraan as $k)
                            <div class="border border-slate-200 p-4 rounded-2xl flex items-center justify-between bg-white hover:border-red-300 transition shadow-sm">
                                <div>
                                    <p class="font-black text-blue-950 uppercase tracking-tight">{{ $k->plat_nomor }}</p>
                                    <p class="text-xs font-bold text-slate-500">{{ $k->tipe }} ({{ $k->tahun }})</p>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Edit Button -->
                                    <button @click="openEditModal(@js($k))" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors" title="Edit Motor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('user.kendaraan.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus motor ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Motor">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Riwayat Section -->
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                        <h3 class="text-lg font-extrabold text-blue-950 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat & Antrean
                        </h3>
                        <a href="{{ route('user.history') }}" class="text-xs font-bold text-red-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($bookings->take(5) as $b)
                            <div class="border border-slate-200 rounded-2xl p-5 bg-white hover:border-red-300 transition shadow-sm group">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-50 text-blue-950 rounded-xl flex items-center justify-center font-black border border-slate-100 shadow-sm">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-blue-950 uppercase tracking-tight">{{ $b->kendaraan->plat_nomor }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        @if($b->status === 'pending')
                                            <span class="px-3 py-1 bg-orange-50 text-orange-600 border border-orange-100 rounded-full text-[10px] font-black uppercase tracking-widest">Menunggu</span>
                                        @elseif($b->status === 'approved')
                                            <span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[10px] font-black uppercase tracking-widest">Disetujui</span>
                                        @elseif($b->status === 'in_progress')
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-pulse"></span> Servis
                                            </span>
                                        @elseif($b->status === 'completed')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-widest">Selesai</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-[10px] font-black uppercase tracking-widest">Batal</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="pl-[52px]">
                                    <p class="text-xs text-slate-500 font-medium line-clamp-1 italic mb-3">"{{ $b->keluhan }}"</p>
                                    <button @click="openModal(@js($b))" class="w-full py-2 bg-slate-100 text-blue-950 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-300 group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 transition-all shadow-sm">
                                        Detail & Progres
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-sm font-bold text-slate-400 uppercase tracking-widest">Belum ada antrean</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Booking Form -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-[24px] border border-red-200 shadow-xl shadow-red-100/50 overflow-hidden sticky top-24">
                    <div class="bg-red-600 p-6 text-white text-center">
                        <h3 class="text-xl font-bold mb-1">Buat Booking Servis</h3>
                        <p class="text-red-100 text-sm font-thin">Isi form di bawah untuk memesan slot</p>
                    </div>
                    <div class="p-6">
                        @if($kendaraan->count() > 0)
                        <form action="{{ route('user.booking.store') }}" method="POST" class="space-y-5" 
                              x-data="{ 
                                  packages: [], 
                                  selectedPackages: [],
                                  get calculateTotal() {
                                      return this.packages.filter(p => this.selectedPackages.includes(p.id.toString())).reduce((sum, p) => sum + parseInt(p.harga_jasa), 0);
                                  },
                                  get needsEstimation() {
                                      return this.packages.filter(p => this.selectedPackages.includes(p.id.toString())).some(p => p.nama_paket.toLowerCase().includes('servis') || p.nama_paket.toLowerCase().includes('cek'));
                                  }
                              }" 
                              x-init="fetch('{{ route('user.api.packages') }}').then(r => r.json()).then(d => packages = d)">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Motor</label>
                                <select name="kendaraan_id" required class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold focus:ring-red-500">
                                    @foreach($kendaraan as $k)
                                        <option value="{{ $k->id }}">{{ $k->plat_nomor }} - {{ $k->tipe }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal</label>
                                <select name="tanggal" required class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold focus:ring-red-500">
                                    @foreach($activeSchedules as $s)
                                        @if($s->kapasitas_menit - $s->terpakai_menit > 0)
                                            <option value="{{ $s->tanggal }}">{{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Paket Servis</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    <template x-for="p in packages" :key="p.id">
                                        <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="selectedPackages.includes(p.id.toString()) ? 'border-red-500 bg-red-50' : 'bg-white'">
                                            <input type="checkbox" name="paket_ids[]" :value="p.id" x-model="selectedPackages" class="mt-1 w-4 h-4 text-red-600 rounded">
                                            <div class="flex-1">
                                                <div class="flex justify-between text-xs font-bold">
                                                    <span class="text-blue-950" x-text="p.nama_paket"></span>
                                                    <span class="text-red-600" x-text="'Rp' + parseInt(p.harga_jasa).toLocaleString()"></span>
                                                </div>
                                                <div class="mt-1.5 text-[10px] text-slate-500 font-normal leading-relaxed border-t border-slate-100 pt-1" 
                                                     x-show="selectedPackages.includes(p.id.toString())" 
                                                     x-transition 
                                                     x-text="p.deskripsi">
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <textarea name="keluhan" rows="3" placeholder="Keluhan motor Anda..." class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold"></textarea>

                            <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-xl font-black uppercase tracking-widest shadow-lg hover:bg-red-700 transition-all">
                                Daftar Antrean
                            </button>
                        </form>
                        @else
                        <div class="text-center py-8">
                            <p class="font-bold text-blue-950 mb-2">Garasi Kosong</p>
                            <button @click="openForm = true" class="text-sm text-red-600 font-bold underline">Tambah motor dulu</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    <!-- MODAL (Timeline & Approval) -->
    <template x-teleport="body">
        <div x-cloak x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
            <template x-if="showModal">
                <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] my-8">
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
                                 'bg-emerald-50 text-emerald-700 border-emerald-100': activeBooking?.status === 'completed',
                                 'bg-red-50 text-red-700 border-red-100': activeBooking?.status === 'rejected',
                             }"
                             x-text="
                                 activeBooking?.status === 'pending' ? 'Menunggu Konfirmasi' :
                                 activeBooking?.status === 'approved' ? 'Disetujui / Menunggu Servis' :
                                 activeBooking?.status === 'in_progress' ? 'Sedang Dikerjakan' :
                                 activeBooking?.status === 'completed' ? 'Selesai' : 'Ditolak'
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

                        <!-- Quotation approval section -->
                        <template x-if="activeBooking?.quotation_status === 'sent'">
                            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
                                <h4 class="text-sm font-black text-orange-900 mb-2">Persetujuan Estimasi Biaya & Sparepart</h4>
                                <p class="text-xs text-orange-850 font-medium mb-4" x-text="'Catatan Kerusakan: ' + activeBooking?.catatan_kerusakan"></p>
                                
                                <form :action="'/user/booking/' + activeBooking?.id + '/approve-quotation'" method="POST" class="space-y-4">
                                    @csrf @method('PATCH')
                                    
                                    <!-- List of spare parts with checkboxes -->
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Sparepart yang Disetujui:</p>
                                        <template x-for="item in activeBooking?.pemakaian_barang" :key="item.id">
                                            <label class="flex items-center gap-3 p-3 border border-orange-200 rounded-xl cursor-pointer hover:bg-orange-100/50 transition-colors bg-white">
                                                <input type="checkbox" name="approved_items[]" :value="item.pivot.id.toString()" x-model="approvedItems" class="w-4 h-4 text-green-600 rounded border-orange-300 focus:ring-green-500">
                                                <div class="flex-1 flex justify-between text-sm">
                                                    <span class="font-medium text-blue-950">
                                                        <span x-text="item.nama_barang"></span>
                                                        <span class="text-xs text-slate-500 ml-1" x-text="'x' + item.pivot.jumlah"></span>
                                                    </span>
                                                    <span class="font-bold text-slate-700" x-text="'Rp ' + (parseInt(item.harga_satuan) * parseInt(item.pivot.jumlah)).toLocaleString('id-ID')"></span>
                                                </div>
                                            </label>
                                        </template>
                                        <div x-show="!activeBooking?.pemakaian_barang || activeBooking?.pemakaian_barang.length === 0" class="text-xs text-slate-500 italic">Tidak ada sparepart tambahan yang ditawarkan.</div>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-2">
                                        <button name="status" value="approved" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-sm shadow transition-all">Setujui Estimasi</button>
                                        <button name="status" value="rejected" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm shadow transition-all">Tolak Semua</button>
                                    </div>
                                </form>
                            </div>
                        </template>

                        <!-- Show Invoice summary if already completed or has active items -->
                        <div class="border-t border-slate-200 pt-6" x-show="activeBooking?.status === 'completed' || (activeBooking?.pemakaian_barang && activeBooking?.pemakaian_barang.length > 0)">
                            <h4 class="text-sm font-black text-blue-950 mb-4 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Rincian Biaya
                                </span>
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full" x-show="activeBooking?.nomor_invoice" x-text="activeBooking?.nomor_invoice"></span>
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
                                        <div class="flex justify-between text-sm mb-1" x-show="activeBooking?.quotation_status === 'sent' ? approvedItems.includes(item.pivot.id.toString()) : (item.pivot.is_approved == 1 || item.pivot.is_approved == true)">
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
    </template>

    <!-- MODAL EDIT KENDARAAN -->
    <template x-teleport="body">
        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
            <template x-if="showEditModal">
                <div @click.away="showEditModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden flex flex-col my-8">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                        <div>
                            <h3 class="text-lg font-black text-blue-950">Edit Kendaraan</h3>
                            <p class="text-xs text-slate-500 font-medium">Plat Nomor: <span class="font-bold text-slate-700" x-text="editVehicle?.plat_nomor"></span></p>
                        </div>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500 bg-white rounded-full p-2 shadow-sm border border-slate-100">&times;</button>
                    </div>
                    
                    <div class="p-6">
                        <form :action="'/user/kendaraan/' + editVehicle?.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Plat Nomor</label>
                                <input type="text" name="plat_nomor" x-model="editVehicle.plat_nomor" :disabled="editVehicle?.bookings_count > 0" required class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm uppercase focus:ring-red-500 focus:border-red-500 disabled:bg-slate-100 disabled:text-slate-500">
                                <p x-show="editVehicle?.bookings_count > 0" class="text-[10px] text-amber-600 mt-1 font-medium leading-normal">Plat nomor tidak dapat diubah karena motor ini sudah memiliki riwayat transaksi/booking.</p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tipe Motor (Honda)</label>
                                <input type="text" name="tipe" x-model="editVehicle.tipe" required class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tahun Pembuatan</label>
                                <input type="number" name="tahun" x-model="editVehicle.tahun" required min="1990" max="{{ date('Y') + 1 }}" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <input type="hidden" name="merk" value="Honda">
                            
                            <button type="submit" class="w-full py-3 bg-blue-950 text-white rounded-xl font-bold hover:bg-blue-900 transition-colors shadow">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </template>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dashboardManager', () => ({
                    showModal: false,
                    activeBooking: null,
                    approvedItems: [],
                    showEditModal: false,
                    editVehicle: { plat_nomor: '', tipe: '', tahun: '', bookings_count: 0 },
                    openModal(b) {
                        this.activeBooking = b;
                        this.showModal = true;
                        this.approvedItems = [];
                        if (b && b.pemakaian_barang) {
                            b.pemakaian_barang.forEach(item => {
                                if (item.pivot && (item.pivot.is_approved == 1 || item.pivot.is_approved == true)) {
                                    this.approvedItems.push(item.pivot.id.toString());
                                }
                            });
                        }
                    },
                    openEditModal(v) {
                        this.editVehicle = { ...v };
                        this.showEditModal = true;
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
                                if (this.activeBooking.quotation_status === 'sent') {
                                    if (this.approvedItems.includes(item.pivot.id.toString())) {
                                        totalSparepart += parseInt(item.harga_satuan || 0) * parseInt(item.pivot.jumlah || 0);
                                    }
                                } else {
                                    if (item.pivot && (item.pivot.is_approved == 1 || item.pivot.is_approved == true)) {
                                        totalSparepart += parseInt(item.harga_satuan || 0) * parseInt(item.pivot.jumlah || 0);
                                    }
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