<x-sidebar-layout>
    <x-slot name="title">Dashboard Pelanggan</x-slot>

    <div x-data="{
            showModal: false,
            activeBooking: null,
            openModal(b) {
                this.activeBooking = b;
                this.showModal = true;
            }
        }" class="space-y-8">

        <!-- Welcome Banner -->
        <div class="bg-blue-950 p-8 sm:p-10 rounded-[32px] text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Halo, {{ auth()->user()->name }}!</h2>
                <p class="text-blue-300 font-thin max-w-xl leading-relaxed">Selamat datang di SkensaMotoHub. Anda dapat mendaftarkan kendaraan, melihat jadwal booking, dan melacak proses servis motor Anda secara real-time.</p>
            </div>
        </div>

        @php
            $actionNeeded = \App\Models\Booking::where('user_id', auth()->id())
                ->where('status', 'in_progress')
                ->where('quotation_status', 'sent')
                ->first();
        @endphp

        @if($actionNeeded)
        <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-[24px] shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-orange-800">Menunggu Persetujuan Anda</h3>
                <p class="text-sm text-orange-700 font-medium mt-1">Mekanik telah selesai melakukan estimasi biaya untuk <span class="font-bold">{{ $actionNeeded->kendaraan->plat_nomor }}</span>. Silakan periksa rincian biaya dan berikan persetujuan agar servis dapat segera dimulai.</p>
            </div>
            <button @click="openModal({{ $actionNeeded->toJson() }})" class="px-5 py-2.5 bg-orange-500 text-white rounded-full text-sm font-bold shadow hover:bg-orange-600 transition-all ml-4 shrink-0">
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
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Plat Nomor</label>
                                <input type="text" name="plat_nomor" required placeholder="DK 1234 ABC" class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm uppercase">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tipe (Honda)</label>
                                <select name="tipe" required class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm">
                                    <option value="BeAT">BeAT</option><option value="Vario 125">Vario 125</option><option value="Scoopy">Scoopy</option><option value="PCX 160">PCX 160</option>
                                </select>
                            </div>
                            <input type="hidden" name="merk" value="Honda">
                            <input type="hidden" name="tahun" value="2022">
                            <button type="submit" class="col-span-2 py-3 bg-blue-950 text-white rounded-xl font-bold">Simpan Kendaraan</button>
                        </form>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($kendaraan as $k)
                            <div class="border border-slate-200 p-4 rounded-2xl flex items-center justify-between bg-white hover:border-red-300 transition">
                                <div>
                                    <p class="font-black text-blue-950 uppercase">{{ $k->plat_nomor }}</p>
                                    <p class="text-xs font-bold text-slate-500">{{ $k->tipe }}</p>
                                </div>
                                <form action="{{ route('user.kendaraan.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus motor?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM9 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17V12a2 2 0 0 0-2-2h-3L8 7H5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10 7 13M12 17h5"/></svg></button>
                                </form>
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
                            <div class="border border-slate-100 rounded-2xl p-5 hover:shadow-md transition-all group bg-white">
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
                                    <button @click="openModal({{ $b->toJson() }})" class="w-full py-2 bg-slate-50 text-blue-950 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-100 group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 transition-all">
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
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Kendaraan</label>
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
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <textarea name="keluhan" rows="3" placeholder="Keluhan motor Anda..." class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold"></textarea>

                            <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-xl font-black uppercase tracking-widest shadow-lg hover:bg-red-700 transition-all">
                                Ajukan Booking
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

    </div>

    <!-- MODAL (Timeline & Approval) -->
    <div x-cloak x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-black text-blue-950">Detail Servis #<span x-text="activeBooking?.id"></span></h3>
                <button @click="showModal = false" class="text-2xl font-bold text-slate-400">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto space-y-6">
                <div class="rounded-xl p-4 text-center font-bold text-sm bg-blue-50 text-blue-700 uppercase tracking-widest" x-text="activeBooking?.status"></div>
                <div>
                    <h4 class="text-sm font-black text-blue-950 mb-4">Timeline Progres</h4>
                    <div class="border-l-2 border-slate-200 ml-2 space-y-4">
                        <template x-for="log in activeBooking?.progres">
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold" x-text="new Date(log.created_at).toLocaleString()"></p>
                                <p class="text-sm font-medium text-slate-700" x-text="log.status_log"></p>
                            </div>
                        </template>
                    </div>
                </div>
                <template x-if="activeBooking?.quotation_status === 'sent'">
                    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
                        <h4 class="text-sm font-black text-orange-900 mb-2">Persetujuan Estimasi</h4>
                        <p class="text-xs font-medium mb-4" x-text="activeBooking?.catatan_kerusakan"></p>
                        <form :action="'/user/booking/' + activeBooking?.id + '/approve-quotation'" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <button name="status" value="approved" class="flex-1 py-2 bg-green-600 text-white rounded-lg font-bold text-xs">Setujui</button>
                            <button name="status" value="rejected" class="flex-1 py-2 bg-red-600 text-white rounded-lg font-bold text-xs">Tolak</button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-sidebar-layout>