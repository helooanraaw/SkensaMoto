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
            <div class="absolute top-0 right-0 w-64 h-64"></div>
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
                
                <!-- Data Kendaraan -->
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden" x-data="{ openForm: false }">
                    <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4 bg-slate-50">
                        <h3 class="text-lg font-black text-blue-950 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Garasi Kendaraan
                        </h3>
                        <button @click="openForm = !openForm" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition">
                            + Tambah Motor
                        </button>
                    </div>

                    <!-- Form Tambah Kendaraan -->
                    <div x-cloak x-show="openForm" class="p-6 border-b border-slate-100 bg-red-50/30">
                        <form action="{{ route('user.kendaraan.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Plat Nomor</label>
                                <input type="text" name="plat_nomor" required placeholder="DK 1234 ABC" class="w-full border-slate-200 rounded-xl px-4 py-2.5 bg-white text-blue-950 font-bold focus:ring-red-500 focus:border-red-500 shadow-sm uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Merek</label>
                                <input type="text" name="merk" value="Honda" readonly class="w-full border-slate-200 rounded-xl px-4 py-2.5 bg-slate-100 text-slate-500 font-bold focus:ring-0 focus:border-slate-200 shadow-sm cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Tipe (Khusus Honda)</label>
                                <select name="tipe" required class="w-full border-slate-200 rounded-xl px-4 py-2.5 bg-white text-blue-950 font-bold focus:ring-red-500 focus:border-red-500 shadow-sm">
                                    <option value="">-- Pilih Tipe --</option>
                                    <optgroup label="Matic">
                                        <option value="BeAT">BeAT</option>
                                        <option value="Vario 125">Vario 125</option>
                                        <option value="Vario 160">Vario 160</option>
                                        <option value="Scoopy">Scoopy</option>
                                        <option value="PCX 160">PCX 160</option>
                                        <option value="ADV 160">ADV 160</option>
                                        <option value="Genio">Genio</option>
                                    </optgroup>
                                    <optgroup label="Bebek">
                                        <option value="Supra X 125">Supra X 125</option>
                                        <option value="Revo">Revo</option>
                                        <option value="Supra GTR">Supra GTR</option>
                                    </optgroup>
                                    <optgroup label="Sport">
                                        <option value="CB150R">CB150R</option>
                                        <option value="CBR150R">CBR150R</option>
                                        <option value="CBR250RR">CBR250RR</option>
                                        <option value="CRF150L">CRF150L</option>
                                        <option value="Sonic 150R">Sonic 150R</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Tahun</label>
                                <input type="number" name="tahun" required placeholder="2020" class="w-full border-slate-200 rounded-xl px-4 py-2.5 bg-white text-blue-950 font-bold focus:ring-red-500 focus:border-red-500 shadow-sm">
                            </div>
                            <div class="sm:col-span-2 mt-2">
                                <button type="submit" class="px-6 py-3 bg-blue-950 text-white rounded-xl text-sm font-bold shadow-md hover:bg-blue-900 transition w-full">Simpan Kendaraan</button>
                            </div>
                        </form>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($kendaraan as $k)
                            <div class="border border-slate-200 p-4 rounded-2xl flex flex-col justify-center gap-4 hover:border-red-300 hover:shadow-md transition bg-white relative overflow-hidden group" x-data="{ isEditing: false }">
                                <div x-show="!isEditing" class="relative z-10 flex items-center justify-between w-full">
                                    <div>
                                        <p class="font-black text-blue-950 text-lg uppercase">{{ $k->plat_nomor }}</p>
                                        <p class="text-sm font-bold text-slate-500">{{ $k->merk }} {{ $k->tipe }} ({{ $k->tahun }})</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click="isEditing = true" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('user.kendaraan.destroy', $k) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini? Penghapusan akan gagal jika motor sedang dalam proses servis.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit Form -->
                                <form x-cloak x-show="isEditing" action="{{ route('user.kendaraan.update', $k) }}" method="POST" class="relative z-10 w-full space-y-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase">Plat Nomor</label>
                                            <input type="text" name="plat_nomor" value="{{ $k->plat_nomor }}" required class="w-full text-xs border-slate-200 rounded-lg px-2 py-1.5 focus:ring-red-500 focus:border-red-500 uppercase">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase">Tipe</label>
                                            <select name="tipe" required class="w-full text-xs border-slate-200 rounded-lg px-2 py-1.5 focus:ring-red-500 focus:border-red-500">
                                                <option value="{{ $k->tipe }}">{{ $k->tipe }} (Saat ini)</option>
                                                <optgroup label="Matic"><option value="BeAT">BeAT</option><option value="Vario 125">Vario 125</option><option value="Vario 160">Vario 160</option><option value="Scoopy">Scoopy</option><option value="PCX 160">PCX 160</option><option value="ADV 160">ADV 160</option><option value="Genio">Genio</option></optgroup>
                                                <optgroup label="Bebek"><option value="Supra X 125">Supra X 125</option><option value="Revo">Revo</option><option value="Supra GTR">Supra GTR</option></optgroup>
                                                <optgroup label="Sport"><option value="CB150R">CB150R</option><option value="CBR150R">CBR150R</option><option value="CBR250RR">CBR250RR</option><option value="CRF150L">CRF150L</option><option value="Sonic 150R">Sonic 150R</option></optgroup>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase">Tahun</label>
                                            <input type="number" name="tahun" value="{{ $k->tahun }}" required class="w-full text-xs border-slate-200 rounded-lg px-2 py-1.5 focus:ring-red-500 focus:border-red-500">
                                        </div>
                                        <input type="hidden" name="merk" value="Honda">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 py-1.5 bg-blue-950 text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition shadow-sm">Simpan</button>
                                        <button type="button" @click="isEditing = false" class="flex-1 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">Batal</button>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="sm:col-span-2 text-center py-6 text-slate-500 font-medium">
                                Anda belum mendaftarkan kendaraan. Silakan tambah kendaraan terlebih dahulu.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Booking -->
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                        <h3 class="text-lg font-extrabold text-blue-950">Riwayat Servis & Antrean</h3>
                        <a href="{{ route('user.history') }}" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($bookings as $b)
                            <div class="border border-slate-100 rounded-2xl p-5 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between hover:shadow-md transition">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm font-black text-blue-950 uppercase">{{ $b->kendaraan->plat_nomor }}</span>
                                        <span class="text-xs font-bold text-slate-400">&bull;</span>
                                        <span class="text-sm font-bold text-slate-600">{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</span>
                                    </div>
                                    @if($b->paket_servis->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @foreach($b->paket_servis as $paket)
                                                <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded uppercase tracking-wider">
                                                    {{ $paket->nama_paket }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="text-sm text-slate-500 leading-relaxed font-medium line-clamp-2">"{{ $b->keluhan }}"</p>
                                </div>
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    @if($b->status === 'pending')
                                        <span class="px-4 py-1.5 bg-orange-50 text-orange-600 border border-orange-200 rounded-full text-xs font-bold">Menunggu Persetujuan</span>
                                    @elseif($b->status === 'approved')
                                        <span class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 rounded-full text-xs font-bold">Disetujui</span>
                                    @elseif($b->status === 'in_progress')
                                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-full text-xs font-bold flex items-center gap-2">
                                            <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span> Dikerjakan
                                        </span>
                                    @elseif($b->status === 'completed')
                                        <span class="px-4 py-1.5 bg-green-50 text-green-600 border border-green-200 rounded-full text-xs font-bold">Selesai - Rp{{ number_format($b->total_harga, 0, ',', '.') }}</span>
                                    @else
                                        <span class="px-4 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-full text-xs font-bold">Ditolak</span>
                                    @endif
                                    
                                    <button @click="openModal({{ $b->toJson() }})" class="text-xs font-bold text-red-600 hover:text-red-800 transition-colors underline mt-1">Lihat Detail & Progres</button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-500 font-medium">
                                Belum ada riwayat booking.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Booking Form -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-[24px] border border-red-200 shadow-xl shadow-red-100/50 overflow-hidden sticky top-24">
                    <div class="bg-red-600 p-6 text-white text-center">
                        <h3 class="text-xl font-bold mb-1">Buat Booking Servis</h3>
                        <p class="text-red-100 text-sm font-thin">Isi form di bawah untuk memesan slot servis</p>
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
                                <select name="kendaraan_id" required class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold focus:ring-red-500 focus:border-red-500">
                                    @foreach($kendaraan as $k)
                                        <option value="{{ $k->id }}">{{ $k->plat_nomor }} - {{ $k->merk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Paket Servis</label>
                                <div class="grid grid-cols-1 gap-3 max-h-64 overflow-y-auto pr-2">
                                    <template x-for="p in packages" :key="p.id">
                                        <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="selectedPackages.includes(p.id.toString()) ? 'border-red-500 bg-red-50' : 'bg-white'">
                                            <div class="pt-0.5">
                                                <input type="checkbox" name="paket_ids[]" :value="p.id" x-model="selectedPackages" class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500">
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-bold text-sm text-blue-950" x-text="p.nama_paket"></span>
                                                    <span class="font-black text-sm text-red-600" x-text="`Rp ${parseInt(p.harga_jasa).toLocaleString('id-ID')}`"></span>
                                                </div>
                                                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-2" x-text="p.deskripsi"></p>
                                                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span x-text="`Estimasi: ${p.estimasi_menit} Menit`"></span>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                                <!-- Logic for Cost Estimation -->
                                <div x-show="selectedPackages.length > 0" x-cloak class="mt-4 p-4 rounded-xl border shadow-sm transition-all" :class="needsEstimation ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200'">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg x-show="needsEstimation" class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <svg x-show="!needsEstimation" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <h4 class="font-bold text-sm" :class="needsEstimation ? 'text-orange-800' : 'text-green-800'" x-text="needsEstimation ? 'Estimasi Biaya Awal' : 'Total Biaya Pasti'"></h4>
                                    </div>
                                    <p class="text-2xl font-black mb-1" :class="needsEstimation ? 'text-orange-600' : 'text-green-600'" x-text="`Rp ${calculateTotal.toLocaleString('id-ID')}`"></p>
                                    <p x-show="needsEstimation" class="text-xs font-medium text-orange-700 leading-relaxed">*Ini hanyalah biaya jasa/pengecekan. Estimasi total akhir (jika ada tambahan sparepart) akan dikirimkan oleh mekanik setelah pengecekan kerusakan.</p>
                                    <p x-show="!needsEstimation" class="text-xs font-medium text-green-700 leading-relaxed">*Karena tidak ada pengecekan kerusakan, harga ini bersifat tetap dan sudah final.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Servis</label>
                                <select name="tanggal" required class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold focus:ring-red-500 focus:border-red-500">
                                    <option value="" disabled selected>Pilih Tanggal Tersedia...</option>
                                    @foreach($activeSchedules as $s)
                                        @if($s->kapasitas_menit - $s->terpakai_menit > 0)
                                            <option value="{{ $s->tanggal }}">{{ \Carbon\Carbon::parse($s->tanggal)->format('l, d M Y') }} (Tersedia)</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1 font-bold">*Hanya menampilkan tanggal yang memiliki kuota sisa</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Keluhan / Catatan <span x-show="selectedPackages.length === 0" class="text-red-500">*</span></label>
                                <textarea name="keluhan" rows="3" :required="selectedPackages.length === 0" placeholder="Tuliskan keluhan motor Anda (Wajib jika tidak pilih paket)" class="w-full border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-blue-950 font-bold focus:ring-red-500 focus:border-red-500 placeholder-slate-400"></textarea>
                            </div>

                            <button type="submit" class="w-full px-6 py-4 bg-red-600 text-white rounded-xl text-sm font-black shadow-lg hover:bg-red-700 hover:shadow-xl hover:-translate-y-0.5 transition-all uppercase tracking-widest">
                                Ajukan Booking
                            </button>
                        </form>
                        @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="font-bold text-blue-950 mb-2">Garasi Kosong</p>
                            <p class="text-sm text-slate-500 font-medium mb-4">Silakan tambah kendaraan terlebih dahulu sebelum membuat booking.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- PROGRESS & INVOICE MODAL -->
        <div x-cloak x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
            <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-8 flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-blue-950">Detail Servis <span x-text="'#' + activeBooking?.id"></span></h3>
                        <p class="text-xs text-slate-500 font-medium">Plat Nomor: <span class="font-bold text-slate-700" x-text="activeBooking?.kendaraan?.plat_nomor"></span></p>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-red-500 bg-white rounded-full p-2 shadow-sm border border-slate-100">&times;</button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    
                    <!-- Status Banner -->
                    <div class="rounded-[16px] p-4 text-center font-bold text-sm"
                         :class="{
                             'bg-orange-50 text-orange-700': activeBooking?.status === 'pending',
                             'bg-blue-50 text-blue-700': activeBooking?.status === 'approved',
                             'bg-indigo-50 text-indigo-700': activeBooking?.status === 'in_progress',
                             'bg-green-50 text-green-700': activeBooking?.status === 'completed',
                             'bg-red-50 text-red-700': activeBooking?.status === 'rejected',
                             'bg-slate-50 text-slate-700': activeBooking?.status === 'cancelled'
                         }">
                        Status Saat Ini: <span class="uppercase tracking-widest" x-text="activeBooking?.status"></span>
                    </div>

                    <!-- Timeline Progres -->
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
                            <div x-show="!activeBooking?.progres || activeBooking?.progres.length === 0" class="pl-6 text-sm text-slate-500 italic">Belum ada progres tercatat.</div>
                        </div>
                    </div>

                    <!-- Quotation Approval Form (Show only if quotation sent) -->
                    <div x-show="activeBooking?.quotation_status === 'sent'" class="border-t border-slate-200 pt-6 mt-6">
                        <div class="bg-indigo-50 border border-indigo-200 rounded-[16px] p-5">
                            <h4 class="text-sm font-black text-indigo-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Estimasi Biaya & Persetujuan
                            </h4>
                            
                            <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Catatan Kerusakan (Diagnosis)</p>
                            <p class="text-sm font-medium text-indigo-950 bg-white p-3 rounded-xl border border-indigo-100 mb-4" x-text="activeBooking?.catatan_kerusakan"></p>

                            <form :action="'/user/booking/' + activeBooking?.id + '/approve-quotation'" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2">Jasa Paket Servis (Dasar)</p>
                                <div class="space-y-2 mb-4">
                                    <template x-for="paket in activeBooking?.paket_servis" :key="paket.id">
                                        <div class="flex justify-between p-3 bg-white rounded-xl border border-indigo-100">
                                            <span class="text-sm font-bold text-blue-950" x-text="paket.nama_paket"></span>
                                            <span class="text-sm font-black text-indigo-600" x-text="'Rp ' + parseInt(paket.harga_jasa).toLocaleString('id-ID')"></span>
                                        </div>
                                    </template>
                                    <div x-show="!activeBooking?.paket_servis || activeBooking?.paket_servis.length === 0" class="text-sm text-indigo-600 italic bg-white p-3 rounded-xl border border-indigo-100">Servis Umum (Harga jasa akan ditentukan akhir)</div>
                                </div>
                                
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2">Rekomendasi Tambahan Sparepart (Centang yang disetujui)</p>
                                
                                <div class="space-y-2 mb-5">
                                    <template x-for="item in activeBooking?.pemakaian_barang" :key="item.id">
                                        <label class="flex items-center justify-between p-3 bg-white rounded-xl border border-indigo-100 cursor-pointer hover:border-indigo-300 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" name="approved_items[]" :value="item.id" checked class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                                <span class="text-sm font-bold text-blue-950">
                                                    <span x-text="item.nama_barang"></span>
                                                    <span class="text-xs text-slate-400 font-medium ml-1" x-text="'(x' + item.pivot.jumlah + ')'"></span>
                                                </span>
                                            </div>
                                            <span class="text-sm font-black text-indigo-600" x-text="'Rp ' + (parseInt(item.harga_satuan) * parseInt(item.pivot.jumlah)).toLocaleString('id-ID')"></span>
                                        </label>
                                    </template>
                                    <div x-show="!activeBooking?.pemakaian_barang || activeBooking?.pemakaian_barang.length === 0" class="text-sm text-indigo-600 italic bg-white p-3 rounded-xl border border-indigo-100">Hanya perbaikan, tidak ada ganti sparepart.</div>
                                </div>

                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg transition-colors">
                                    Setujui Estimasi Biaya
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Digital Invoice (Show only if completed) -->
                    <div x-show="activeBooking?.status === 'completed'" class="border-t border-slate-200 pt-6 mt-6">
                        <h4 class="text-sm font-black text-blue-950 mb-4 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Digital Invoice
                            </span>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full" x-text="activeBooking?.nomor_invoice"></span>
                        </h4>
                        
                        <div x-show="activeBooking?.mekanik" class="mb-4 bg-slate-50 p-3 rounded-[12px] border border-slate-200 flex items-center gap-2">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Mekanik:</p>
                            <p class="text-sm font-black text-blue-950 flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span x-text="activeBooking?.mekanik?.name"></span>
                            </p>
                        </div>
                        
                        <div class="bg-slate-50 rounded-[16px] p-5 border border-slate-200">
                            <!-- Paket Servis -->
                            <div class="mb-4">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1">Jasa & Servis</p>
                                <template x-for="paket in activeBooking?.paket_servis" :key="paket.id">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-blue-950" x-text="paket.nama_paket"></span>
                                        <span class="font-bold text-slate-700" x-text="'Rp ' + parseInt(paket.harga_jasa).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                                <div x-show="!activeBooking?.paket_servis || activeBooking?.paket_servis.length === 0" class="text-sm text-slate-500 italic">Servis Umum (Tidak ada paket khusus)</div>
                            </div>

                            <!-- Sparepart -->
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

                            <!-- Total -->
                            <div class="border-t border-slate-300 pt-3 mt-2 flex justify-between items-center">
                                <span class="font-black text-blue-950">TOTAL BIAYA</span>
                                <span class="text-xl font-black text-red-600" x-text="'Rp ' + parseInt(activeBooking?.total_harga || 0).toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <a :href="`/user/booking/${activeBooking?.id}/invoice`" target="_blank" class="px-5 py-2.5 bg-blue-950 hover:bg-blue-900 text-white rounded-xl font-bold shadow-md flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Cetak Invoice PDF
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
