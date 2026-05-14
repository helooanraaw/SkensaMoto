<x-sidebar-layout>
    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-blue-950 tracking-tight">Riwayat Servis</h1>
            <p class="text-slate-500 mt-2">Catatan medis perawatan motor Anda di MotoSkensa.</p>
        </div>

        @if($bookings->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Riwayat</h3>
                <p class="text-slate-500">Motor Anda belum memiliki catatan servis yang telah selesai.</p>
                <a href="{{ route('user.dashboard') }}" class="mt-6 inline-block bg-blue-950 text-white font-bold py-2.5 px-6 rounded-xl hover:bg-blue-900 transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Selesai</span>
                                    <span class="text-sm font-medium text-slate-500">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</span>
                                </div>
                                <h3 class="text-xl font-black text-blue-950">
                                    {{ $booking->kendaraan->merk }} {{ $booking->kendaraan->tipe }}
                                    <span class="text-base font-semibold text-slate-400 ml-2">{{ $booking->kendaraan->plat_nomor }}</span>
                                </h3>
                            </div>
                            <div class="text-left sm:text-right w-full sm:w-auto">
                                <div class="text-sm text-slate-500 mb-1">Nomor Invoice</div>
                                <div class="font-mono font-bold text-slate-800">{{ $booking->nomor_invoice }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Paket Servis</div>
                                <div class="space-y-2">
                                    @foreach($booking->paket_servis as $paket)
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-slate-800">{{ $paket->nama_paket }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-4 rounded-xl">
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Informasi Tambahan</div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Mekanik:</span>
                                        <span class="font-medium text-slate-800">{{ $booking->mekanik->name ?? 'Mekanik MotoSkensa' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Keluhan:</span>
                                        <span class="font-medium text-slate-800 text-right w-2/3 truncate" title="{{ $booking->keluhan }}">{{ $booking->keluhan ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 mt-2 border-t border-slate-200">
                                        <span class="font-bold text-slate-600">Total Biaya:</span>
                                        <span class="font-black text-red-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <a href="{{ route('user.booking.invoice', $booking->id) }}" target="_blank" class="flex items-center gap-2 px-5 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold rounded-xl transition-colors">
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
    </div>
</x-sidebar-layout>
