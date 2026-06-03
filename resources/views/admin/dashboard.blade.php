<x-sidebar-layout>
    <x-slot name="title">{{ auth()->user()->role === 'superadmin' ? 'Dashboard Super Admin' : (auth()->user()->role === 'mekanik' ? 'Dashboard Mekanik' : 'Dashboard Admin') }}</x-slot>

    <div class="space-y-8">
        @php
            $userRole = auth()->user()->role;
            $adminPendingCount = \App\Models\Booking::where('status', 'pending')->count();
            $mekanikReadyCount = \App\Models\Booking::where('status', 'approved')
                        ->orWhere(function($query) {
                            $query->where('status', 'in_progress')->where('quotation_status', 'approved');
                        })->count();
        @endphp

        @if(in_array($userRole, ['superadmin', 'admin']) && $adminPendingCount > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-[24px] shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-red-800">Menunggu Persetujuan Admin</h3>
                <p class="text-sm text-red-700 font-medium mt-1">Terdapat <span class="font-bold">{{ $adminPendingCount }}</span> booking baru yang menunggu persetujuan (Terima/Tolak).</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 bg-red-600 text-white rounded-full text-sm font-bold shadow hover:bg-red-700 transition-all ml-4 shrink-0">
                Lihat Antrean
            </a>
        </div>
        @endif

        @if($userRole === 'mekanik' && $mekanikReadyCount > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-[24px] shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-blue-800">Pekerjaan Siap Dikerjakan</h3>
                <p class="text-sm text-blue-700 font-medium mt-1">Terdapat <span class="font-bold">{{ $mekanikReadyCount }}</span> motor yang siap untuk segera diservis atau diselesaikan.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-full text-sm font-bold shadow hover:bg-blue-700 transition-all ml-4 shrink-0">
                Mulai Pekerjaan
            </a>
        </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Total Booking</p>
                <p class="text-3xl font-black text-blue-950">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Menunggu Persetujuan</p>
                <p class="text-3xl font-black text-orange-500">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Sedang Servis</p>
                <p class="text-3xl font-black text-blue-600">{{ $stats['active_bookings'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-red-200 shadow-sm bg-red-50/50">
                <p class="text-sm font-bold text-red-600 mb-1">Stok Menipis (< 5)</p>
                <p class="text-3xl font-black text-red-600">{{ $stats['inventory_low'] }}</p>
            </div>
        </div>

        @if(in_array($userRole, ['superadmin', 'admin']))
        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Line Chart: Tren Booking -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-black text-blue-950 mb-4">Tren Antrean (7 Hari Terakhir)</h3>
                <div class="relative h-64">
                    <canvas id="bookingTrendChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Paket Populer -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <h3 class="text-lg font-black text-blue-950 mb-4">Popularitas Paket Servis</h3>
                <div class="relative h-64 flex-1 flex items-center justify-center">
                    <canvas id="packageChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Recent Bookings -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h2 class="text-lg font-black text-blue-950">Booking Terbaru</h2>
                    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-red-600 hover:text-red-700">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-sm">
                                <th class="py-4 px-6 font-bold text-slate-500">Pelanggan</th>
                                <th class="py-4 px-6 font-bold text-slate-500">Kendaraan</th>
                                <th class="py-4 px-6 font-bold text-slate-500">Tgl Request</th>
                                <th class="py-4 px-6 font-bold text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($recentBookings as $b)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-blue-950">{{ $b->user->name }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-blue-950">{{ $b->kendaraan->plat_nomor }}</p>
                                    <p class="text-xs text-slate-500">{{ $b->kendaraan->merk }} {{ $b->kendaraan->tipe }}</p>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-700">
                                    {{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($b->status === 'pending')
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">Menunggu</span>
                                    @elseif($b->status === 'approved')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Disetujui</span>
                                    @elseif($b->status === 'in_progress')
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">Dikerjakan</span>
                                    @elseif($b->status === 'completed')
                                        @if(($b->payment_status ?? 'unpaid') === 'paid')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold whitespace-nowrap">Lunas</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold whitespace-nowrap">Belum Bayar</span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500 font-medium">Belum ada data booking.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-blue-950 rounded-2xl shadow-lg overflow-hidden text-white relative self-center">
                <!-- <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-bl-full"></div> -->
                <div class="px-6 py-6 border-b border-white/10 relative z-10">
                    <h2 class="text-lg font-black text-white">Jadwal Hari Ini</h2>
                    <p class="text-sm text-blue-200">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                </div>  
                <div class="p-6 relative z-10">
                    @if($jadwalToday)
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-blue-300 uppercase tracking-widest mb-1">Jam Operasional</p>
                                <p class="text-xl font-black">{{ substr($jadwalToday->jam_buka, 0, 5) }} - {{ substr($jadwalToday->jam_tutup, 0, 5) }}</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-xs font-bold text-blue-300 uppercase tracking-widest">Kapasitas Waktu</p>
                                    <p class="text-sm font-bold">{{ $jadwalToday->terpakai_menit }} / {{ $jadwalToday->kapasitas_menit }} Menit</p>
                                </div>
                                <div class="w-full bg-blue-900 rounded-full h-3">
                                    @php
                                        $percentage = ($jadwalToday->kapasitas_menit > 0) ? ($jadwalToday->terpakai_menit / $jadwalToday->kapasitas_menit) * 100 : 0;
                                        $color = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-orange-500' : 'bg-green-400');
                                    @endphp
                                    <div class="{{ $color }} h-3 rounded-full transition-all" style="width: {{ min(100, $percentage) }}%"></div>
                                </div>
                            </div>
                            
                            <div class="pt-5 border-t border-white/10 mt-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $percentage >= 100 ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-green-400/20 text-green-400 border border-green-400/30' }} flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest mb-0.5">Status Pendaftaran</p>
                                            @if($percentage >= 100)
                                                <p class="text-sm font-black text-white">Kapasitas Penuh</p>
                                            @else
                                                <p class="text-sm font-black text-white">Tersedia <span class="text-green-400">~{{ max(0, floor(($jadwalToday->kapasitas_menit - $jadwalToday->terpakai_menit) / 60)) }} Motor</span></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('admin.bookings.index') }}" class="block w-full py-2.5 px-4 border border-white/20 text-white text-center rounded-xl text-sm font-bold hover:bg-white/10 transition-colors">
                                    Lihat Antrean Masuk
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3 text-blue-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="font-bold mb-1">Belum Ada Jadwal</p>
                            <p class="text-sm text-blue-300">Admin belum mengeset jadwal kapasitas bengkel untuk hari ini.</p>
                            <a href="{{ route('admin.schedules.index') }}" class="mt-4 inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition">Atur Jadwal</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        @if(in_array($userRole, ['superadmin', 'admin']))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tren Booking Chart
                const trendCtx = document.getElementById('bookingTrendChart');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($chartDates) !!}.reverse(),
                            datasets: [{
                                label: 'Jumlah Antrean',
                                data: {!! json_encode($chartBookings) !!}.reverse(),
                                borderColor: '#dc2626', // red-600
                                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#dc2626',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1, color: '#64748b', font: { weight: 'bold' } },
                                    grid: { color: '#f1f5f9', drawBorder: false }
                                },
                                x: {
                                    ticks: { color: '#64748b', font: { weight: 'bold' } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }

                // Paket Populer Chart
                const packageCtx = document.getElementById('packageChart');
                if (packageCtx) {
                    const labels = {!! json_encode($packageLabels) !!};
                    const data = {!! json_encode($packageData) !!};

                    // If no data, show empty state
                    if (data.length === 0 || data.reduce((a, b) => a + b, 0) === 0) {
                        packageCtx.parentElement.innerHTML = '<p class="text-slate-400 font-bold text-center">Belum ada data pemakaian paket.</p>';
                    } else {
                        new Chart(packageCtx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: data,
                                    backgroundColor: [
                                        '#dc2626', // red-600
                                        '#172554', // blue-950
                                        '#f97316', // orange-500
                                        '#10b981', // emerald-500
                                        '#64748b', // slate-500
                                        '#eab308'  // yellow-500
                                    ],
                                    borderWidth: 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            color: '#1e293b',
                                            font: { weight: 'bold', size: 11 },
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    }
                                },
                                layout: { padding: 10 }
                            }
                        });
                    }
                }
            });
        </script>
        @endif
    </x-slot>
</x-sidebar-layout>
