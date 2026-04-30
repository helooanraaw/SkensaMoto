<x-sidebar-layout title="Detail Booking #{{ $booking->id }}" subtitle="{{ $booking->motorcycle_type }} · {{ $booking->license_plate }}">

    {{-- Back button --}}
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-950 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="max-w-2xl space-y-6">

            {{-- Info Booking --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                @php
                    $d = \Carbon\Carbon::parse($booking->schedule?->date)->locale('id');
                    $statusColor = match($booking->status) {
                        'menunggu'     => 'bg-yellow-100 text-yellow-700',
                        'dikonfirmasi' => 'bg-blue-100 text-blue-700',
                        'selesai'      => 'bg-green-100 text-green-600',
                        'batal'        => 'bg-red-100 text-red-600',
                        default        => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Jadwal Servis</p>
                        <p class="text-xl font-extrabold text-blue-950">{{ $d->translatedFormat('l, d F Y') }}</p>
                        @if($booking->schedule?->start_time)
                            <p class="text-sm text-slate-400">{{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WITA</p>
                        @endif
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $statusColor }}">{{ ucfirst($booking->status) }}</span>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Motor</p>
                        <p class="font-bold text-blue-950">{{ $booking->motorcycle_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Plat Nomor</p>
                        <p class="font-bold text-blue-950">{{ $booking->license_plate }}</p>
                    </div>
                    @if($booking->complaint)
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 font-bold uppercase">Keluhan</p>
                        <p class="text-slate-600">{{ $booking->complaint }}</p>
                    </div>
                    @endif
                    @if($booking->rejection_reason)
                    <div class="col-span-2 bg-red-50 border border-red-100 rounded-xl p-3">
                        <p class="text-xs text-red-500 font-bold uppercase">Alasan Penolakan</p>
                        <p class="text-red-600 text-sm mt-0.5">{{ $booking->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- CHAT --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-extrabold text-blue-950">💬 Chat dengan Admin</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tanyakan langsung tentang booking Anda</p>
                </div>

                {{-- Pesan --}}
                <div id="chatBox" class="px-6 py-5 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($booking->messages as $msg)
                        <div class="flex {{ $msg->is_admin ? 'justify-start' : 'justify-end' }} gap-2">
                            @if($msg->is_admin)
                                <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold shrink-0">A</div>
                            @endif
                            <div class="{{ $msg->is_admin ? 'bg-slate-100 text-slate-700 rounded-tl-none' : 'bg-red-600 text-white rounded-tr-none' }} rounded-2xl px-4 py-3 max-w-xs text-sm">
                                <p>{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1 {{ $msg->is_admin ? 'text-slate-400' : 'text-red-200' }}">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-300 text-sm py-8">Belum ada pesan. Mulai chat dengan admin!</p>
                    @endforelse
                </div>

                {{-- Input Chat --}}
                <div class="px-6 py-4 border-t border-slate-100">
                    <form method="POST" action="{{ route('bookings.messages.send', $booking) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="message" placeholder="Ketik pesan..." required
                            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl transition">Kirim</button>
                    </form>
                </div>
            </div>
    </div>

    <script>
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>

</x-sidebar-layout>
