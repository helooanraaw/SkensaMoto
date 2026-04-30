<x-sidebar-layout title="Dashboard Admin" subtitle="Selamat datang kembali, {{ Auth::user()->name }}!">

    <div class="space-y-6">

            {{-- STATISTIK --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Total Jadwal</p><p class="text-3xl font-extrabold text-blue-950">{{ $totalSchedules }}</p></div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Kuota Hari Ini</p><p class="text-3xl font-extrabold text-blue-950">{{ $todayQuota }}</p></div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Hari Tersedia</p><p class="text-3xl font-extrabold text-blue-950">{{ $tersediaCount }}</p></div>
                </div>
                <div class="bg-white rounded-2xl border {{ $pendingCount > 0 ? 'border-yellow-200 bg-yellow-50' : 'border-slate-100' }} shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Menunggu Review</p><p class="text-3xl font-extrabold text-blue-950">{{ $pendingCount }}</p></div>
                </div>
            </div>

            {{-- PANEL BOOKING USER --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-blue-950">📬 Daftar Booking Masuk</h3>
                        <p class="text-sm text-slate-400 mt-0.5">Review booking dari pelanggan, setujui atau tolak</p>
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $pendingCount }} Menunggu</span>
                    @endif
                </div>

                @forelse($allBookings as $booking)
                    @php
                        $d = \Carbon\Carbon::parse($booking->schedule?->date)->locale('id');
                        $unreadCount = $booking->messages->where('is_admin', false)->where('is_read', false)->count();
                        $statusColor = match($booking->status) {
                            'menunggu'     => 'bg-yellow-100 text-yellow-700',
                            'dikonfirmasi' => 'bg-blue-100 text-blue-700',
                            'selesai'      => 'bg-green-100 text-green-600',
                            'batal'        => 'bg-red-100 text-red-500',
                            default        => 'bg-slate-100 text-slate-500',
                        };
                    @endphp
                    <div class="px-8 py-5 border-b border-slate-50 last:border-0 {{ $booking->status === 'menunggu' ? 'bg-yellow-50/40' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            {{-- Tanggal --}}
                            <div class="shrink-0 text-center bg-red-50 border border-red-100 rounded-xl px-4 py-3 w-24">
                                <p class="text-xs font-black text-red-400 uppercase">{{ $d->translatedFormat('D') }}</p>
                                <p class="text-xl font-extrabold text-blue-950">{{ $d->format('j') }}</p>
                                <p class="text-xs text-slate-400">{{ $d->format('M') }}</p>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <span class="font-extrabold text-blue-950">{{ $booking->user?->name }}</span>
                                    <span class="text-xs text-slate-400">·</span>
                                    <span class="text-sm font-bold text-slate-600">{{ $booking->motorcycle_type }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold">{{ $booking->license_plate }}</span>
                                </div>
                                @if($booking->complaint)
                                    <p class="text-sm text-slate-400">🔧 {{ Str::limit($booking->complaint, 70) }}</p>
                                @endif
                                @if($booking->rejection_reason)
                                    <p class="text-xs text-red-400 mt-0.5">Alasan ditolak: {{ $booking->rejection_reason }}</p>
                                @endif
                                <p class="text-xs text-slate-300 mt-0.5">{{ $booking->created_at->diffForHumans() }}</p>
                            </div>

                            {{-- Status + Aksi --}}
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">{{ ucfirst($booking->status) }}</span>

                                <div class="flex gap-2 flex-wrap justify-end">
                                    {{-- Chat --}}
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="relative text-xs font-bold text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                                        💬 Chat
                                        @if($unreadCount > 0)
                                            <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                                        @endif
                                    </a>

                                    @if($booking->status === 'menunggu')
                                        {{-- Approve --}}
                                        <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-bold text-green-700 border border-green-200 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">✅ Setuju</button>
                                        </form>
                                        {{-- Reject (buka panel alasan) --}}
                                        <button onclick="openReject({{ $booking->id }})" class="text-xs font-bold text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">❌ Tolak</button>
                                    @endif

                                    @if($booking->status === 'dikonfirmasi')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="text-xs font-bold text-green-700 border border-green-300 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">🎉 Selesai</button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Panel input alasan tolak --}}
                                <div id="reject-{{ $booking->id }}" class="hidden w-full mt-1">
                                    <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Tulis alasan penolakan..." required
                                            class="flex-1 border border-red-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 min-w-0">
                                        <button type="submit" class="text-xs bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg whitespace-nowrap">Kirim</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-12 text-center text-slate-400">
                        <p class="text-4xl mb-2">📭</p>
                        <p class="font-semibold">Belum ada booking masuk dari pelanggan.</p>
                    </div>
                @endforelse
            </div>


            {{-- PROGRESS SERVIS HARI INI --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-blue-950">🔧 Progress Servis Hari Ini</h3>
                        <p class="text-sm text-slate-400 mt-0.5">
                            {{ $todaySchedule ? \Carbon\Carbon::parse($todaySchedule->date)->translatedFormat('l, d F Y') : 'Tidak ada jadwal hari ini' }}
                        </p>
                    </div>
                    @if($todaySchedule && $todayBookings->count() > 0)
                        @php
                            $selesai = $todayBookings->where('status','selesai')->count();
                            $total   = $todayBookings->count();
                            $pct     = $total > 0 ? round($selesai / $total * 100) : 0;
                        @endphp
                        <div class="text-right">
                            <p class="text-2xl font-extrabold text-blue-950">{{ $selesai }}/{{ $total }}</p>
                            <p class="text-xs text-slate-400">Motor Selesai</p>
                        </div>
                    @endif
                </div>

                @if(!$todaySchedule)
                    <div class="px-8 py-10 text-center text-slate-400">
                        <p class="font-semibold">Tidak ada jadwal untuk hari ini.</p>
                    </div>
                @elseif($todayBookings->isEmpty())
                    <div class="px-8 py-10 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        <p class="font-semibold">Belum ada booking masuk untuk hari ini.</p>
                    </div>
                @else
                    {{-- Progress Bar --}}
                    <div class="px-8 pt-5 pb-2">
                        <div class="w-full bg-slate-100 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">{{ $pct }}% selesai dikerjakan</p>
                    </div>

                    {{-- Daftar Antrian --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($todayBookings as $i => $booking)
                            @php
                                $statusColor = match($booking->status) {
                                    'menunggu'     => 'bg-yellow-100 text-yellow-700',
                                    'dikonfirmasi' => 'bg-blue-100 text-blue-700',
                                    'selesai'      => 'bg-green-100 text-green-700',
                                    'batal'        => 'bg-red-100 text-red-600',
                                    default        => 'bg-slate-100 text-slate-600',
                                };
                                $statusLabel = match($booking->status) {
                                    'menunggu'     => '⏳ Menunggu',
                                    'dikonfirmasi' => '🔧 Sedang Dikerjakan',
                                    'selesai'      => '✅ Selesai',
                                    'batal'        => '❌ Batal',
                                    default        => $booking->status,
                                };
                            @endphp
                            <div class="px-8 py-4 flex items-center gap-4 {{ $booking->status === 'dikonfirmasi' ? 'bg-blue-50' : '' }}">
                                {{-- Nomor Antrian --}}
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-extrabold text-sm flex items-center justify-center shrink-0">
                                    {{ $i + 1 }}
                                </div>

                                {{-- Info Motor --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-blue-950 truncate">
                                        {{ $booking->user?->name ?? 'Pelanggan' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        🏍️ {{ $booking->motorcycle_type }} &nbsp;·&nbsp;
                                        🔖 {{ strtoupper($booking->license_plate) }}
                                        @if($booking->complaint)
                                            &nbsp;·&nbsp; {{ Str::limit($booking->complaint, 40) }}
                                        @endif
                                    </p>
                                </div>

                                {{-- Badge Status --}}
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }} whitespace-nowrap">
                                    {{ $statusLabel }}
                                </span>

                                {{-- Tombol Ganti Status --}}
                                <div class="flex gap-1 shrink-0">
                                    @if($booking->status === 'menunggu')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="dikonfirmasi">
                                            <button type="submit" class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded-lg transition">Kerjakan</button>
                                        </form>
                                    @elseif($booking->status === 'dikonfirmasi')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="text-xs bg-green-600 hover:bg-green-700 text-white font-bold px-3 py-1.5 rounded-lg transition">Selesai</button>
                                        </form>
                                    @elseif($booking->status === 'selesai')
                                        <span class="text-xs text-slate-300 font-medium">✓ Done</span>
                                    @endif
                                    @if($booking->status !== 'selesai' && $booking->status !== 'batal')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="batal">
                                            <button type="submit" class="text-xs bg-red-50 hover:bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg transition border border-red-200">Batal</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- TABEL JADWAL --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-blue-950">📅 Manajemen Jadwal</h3>
                        <p class="text-sm text-slate-400 mt-0.5">Tambah, ubah, atau hapus jadwal operasional</p>
                    </div>
                    <button onclick="openModal()" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-full shadow-md transition hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Jadwal
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Hari & Tanggal</th>
                                <th class="px-6 py-4">Jam Operasional</th>
                                <th class="px-6 py-4 text-center">Kuota</th>
                                <th class="px-6 py-4 text-center">Booking Masuk</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($schedules as $schedule)
                                @php
                                    $d = \Carbon\Carbon::parse($schedule->date)->locale('id');
                                    $isToday = $d->isToday();
                                @endphp
                                <tr class="hover:bg-slate-50 transition {{ $isToday ? 'bg-red-50/50' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($isToday)<span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>@endif
                                            <div>
                                                <p class="font-bold text-blue-950">{{ $d->translatedFormat('l') }}</p>
                                                <p class="text-slate-400 text-xs">{{ $d->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $schedule->status === 'libur' ? '—' : substr($schedule->start_time,0,5).' – '.substr($schedule->end_time,0,5).' WITA' }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-blue-950">
                                        {{ $schedule->status === 'libur' ? '—' : $schedule->quota.' Motor' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-bold text-blue-950">{{ $schedule->bookings_count }}</span>
                                        <span class="text-slate-400 text-xs"> booking</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($schedule->status === 'libur')
                                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold">Libur</span>
                                        @elseif($schedule->status === 'penuh' || $schedule->quota <= 0)
                                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold">Penuh</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold">Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button onclick='openEditModal(@json($schedule))' class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">Belum ada jadwal. Klik <strong>Tambah Jadwal</strong> untuk mulai.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

    {{-- ====== MODAL POPUP ====== --}}
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-500 px-8 py-6 flex items-center justify-between">
                <div>
                    <h3 id="modalTitle" class="text-xl font-extrabold text-white">Tambah Jadwal</h3>
                    <p class="text-red-100 text-sm mt-0.5">Isi sesuai jadwal operasional bengkel</p>
                </div>
                <button onclick="closeModal()" class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="scheduleForm" method="POST" action="{{ route('admin.schedules.store') }}" class="px-8 py-7 space-y-5">
                @csrf
                <div id="methodField"></div>

                <div>
                    <label class="block text-sm font-bold text-blue-950 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="inp_date" name="date" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                </div>

                <div id="timeFields" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-blue-950 mb-1.5">Jam Mulai</label>
                        <input type="time" id="inp_start" name="start_time" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-blue-950 mb-1.5">Jam Selesai</label>
                        <input type="time" id="inp_end" name="end_time" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                    </div>
                </div>

                <div id="quotaField">
                    <label class="block text-sm font-bold text-blue-950 mb-1.5">Kuota Motor <span class="text-red-500">*</span></label>
                    <input type="number" id="inp_quota" name="quota" min="0" max="100" placeholder="Contoh: 10" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-950 mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([['tersedia','✅','Tersedia','green'],['penuh','🔴','Penuh','red'],['libur','🚫','Libur','slate']] as [$val,$icon,$label,$color])
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="{{ $val }}" class="sr-only peer" onchange="onStatus(this)">
                            <div data-status="{{ $val }}" class="status-card border-2 border-slate-200 rounded-xl py-3 text-center text-sm font-bold text-slate-500 hover:border-{{ $color }}-400 peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-50 peer-checked:text-{{ $color }}-700 transition-all">
                                <span class="block text-xl mb-0.5">{{ $icon }}</span>{{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 border border-slate-200 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('scheduleModal');
        const form  = document.getElementById('scheduleForm');

        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Jadwal Baru';
            form.action = "{{ route('admin.schedules.store') }}";
            document.getElementById('methodField').innerHTML = '';
            form.reset();
            toggleLibur(false);
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }

        function openEditModal(s) {
            document.getElementById('modalTitle').textContent = 'Edit Jadwal';
            form.action = `/admin/schedules/${s.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('inp_date').value  = s.date;
            document.getElementById('inp_start').value = s.start_time ? s.start_time.substring(0,5) : '';
            document.getElementById('inp_end').value   = s.end_time   ? s.end_time.substring(0,5)   : '';
            document.getElementById('inp_quota').value = s.quota;
            document.querySelectorAll('input[name="status"]').forEach(r => r.checked = r.value === s.status);
            toggleLibur(s.status === 'libur');
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }

        function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); }

        function onStatus(r) { toggleLibur(r.value === 'libur'); }

        function toggleLibur(isLibur) {
            ['timeFields','quotaField'].forEach(id => {
                const el = document.getElementById(id);
                el.style.opacity = isLibur ? '0.3' : '1';
                el.style.pointerEvents = isLibur ? 'none' : 'auto';
            });
        }

        function openReject(id) {
            const panel = document.getElementById('reject-' + id);
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) panel.querySelector('input').focus();
        }

    </script>

</x-sidebar-layout>
