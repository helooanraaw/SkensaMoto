<x-sidebar-layout title="Dashboard Saya" subtitle="Booking & riwayat servis motor Anda">

    <div class="space-y-6">

        {{-- ====== FORM BOOKING BARU ====== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-blue-950">🏍️ Booking Servis Motor</h3>
                    <p class="text-sm text-slate-400 mt-0.5">Pilih jadwal yang tersedia dan isi data motor Anda</p>
                </div>
                <button onclick="toggleForm()" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-full transition hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Booking Sekarang
                </button>
            </div>

            <div id="bookingForm" class="hidden px-6 py-6">
                <form method="POST" action="{{ route('bookings.store') }}" class="space-y-5">
                    @csrf

                    {{-- Pilih Jadwal --}}
                    <div>
                        <label class="block text-sm font-bold text-blue-950 mb-2">Pilih Jadwal <span class="text-red-500">*</span></label>
                        @if($schedules->isEmpty())
                            <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm font-medium">
                                ⚠️ Belum ada jadwal tersedia minggu ini. Cek kembali nanti.
                            </div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($schedules as $s)
                                    @php $d = \Carbon\Carbon::parse($s->date)->locale('id'); @endphp
                                    <label class="cursor-pointer">
                                        <input type="radio" name="workshop_schedule_id" value="{{ $s->id }}" class="sr-only peer" required>
                                        <div class="border-2 border-slate-200 rounded-xl p-4 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all hover:border-red-300">
                                            <p class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ $d->translatedFormat('l') }}</p>
                                            <p class="text-xl font-extrabold text-blue-950">{{ $d->format('j M') }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ substr($s->start_time,0,5) }} – {{ substr($s->end_time,0,5) }} WITA</p>
                                            <p class="text-xs font-bold text-green-600 mt-1">Sisa {{ $s->quota }} kuota</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-blue-950 mb-1.5">Jenis Motor <span class="text-red-500">*</span></label>
                            <input type="text" name="motorcycle_type" placeholder="Contoh: Honda Beat 2020" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                                value="{{ old('motorcycle_type') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-blue-950 mb-1.5">Nomor Plat <span class="text-red-500">*</span></label>
                            <input type="text" name="license_plate" placeholder="Contoh: DK 1234 AB" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition uppercase"
                                value="{{ old('license_plate') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-blue-950 mb-1.5">Keluhan / Kerusakan</label>
                        <textarea name="complaint" rows="3" placeholder="Ceritakan keluhan motor Anda secara detail..."
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition resize-none">{{ old('complaint') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="toggleForm()" class="flex-1 border border-slate-200 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-md" {{ $schedules->isEmpty() ? 'disabled' : '' }}>
                            Kirim Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ====== DAFTAR BOOKING USER ====== --}}
        <div>
            <h3 class="text-base font-extrabold text-blue-950 mb-3">📋 Riwayat Booking Saya</h3>
            <div class="space-y-3">
                @forelse($bookings as $booking)
                    @php
                        $d = \Carbon\Carbon::parse($booking->schedule?->date)->locale('id');
                        $unread = $booking->messages->where('is_admin', true)->where('is_read', false)->count();
                        $statusColor = match($booking->status) {
                            'menunggu'     => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'dikonfirmasi' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'selesai'      => 'bg-green-100 text-green-600 border-green-200',
                            'batal'        => 'bg-red-100 text-red-600 border-red-200',
                            default        => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                        $statusIcon = match($booking->status) {
                            'menunggu'     => '⏳',
                            'dikonfirmasi' => '✅',
                            'selesai'      => '🎉',
                            'batal'        => '❌',
                            default        => '•',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        {{-- Tanggal --}}
                        <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-center shrink-0 w-24">
                            <p class="text-xs font-black text-red-400 uppercase">{{ $d->translatedFormat('D') }}</p>
                            <p class="text-2xl font-extrabold text-blue-950 leading-none">{{ $d->format('j') }}</p>
                            <p class="text-xs text-slate-400">{{ $d->format('M Y') }}</p>
                        </div>

                        {{-- Info Motor --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="font-extrabold text-blue-950">{{ $booking->motorcycle_type }}</span>
                                <span class="text-xs font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $booking->license_plate }}</span>
                            </div>
                            @if($booking->complaint)
                                <p class="text-sm text-slate-400">{{ Str::limit($booking->complaint, 70) }}</p>
                            @endif
                            @if($booking->rejection_reason)
                                <p class="text-xs text-red-500 font-medium mt-0.5">🚫 {{ $booking->rejection_reason }}</p>
                            @endif
                            <p class="text-xs text-slate-300 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>

                        {{-- Status & Aksi --}}
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ $statusIcon }} {{ ucfirst($booking->status) }}
                            </span>
                            <div class="flex gap-2">
                                <a href="{{ route('bookings.show', $booking) }}"
                                    class="relative flex items-center gap-1.5 text-xs font-bold text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                                    💬 Chat
                                    @if($unread > 0)
                                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $unread }}</span>
                                    @endif
                                </a>
                                @if(!in_array($booking->status, ['selesai', 'batal']))
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-bold text-red-500 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-200 py-16 text-center">
                        <p class="text-4xl mb-3">🏍️</p>
                        <p class="font-bold text-slate-500">Belum ada booking.</p>
                        <p class="text-sm text-slate-400 mt-1">Klik <strong>Booking Sekarang</strong> di atas untuk mulai!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        function toggleForm() {
            document.getElementById('bookingForm').classList.toggle('hidden');
        }
        @if($errors->any()) toggleForm(); @endif
    </script>

</x-sidebar-layout>
