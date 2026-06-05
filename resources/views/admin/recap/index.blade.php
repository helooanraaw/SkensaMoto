<x-sidebar-layout>
    <x-slot name="title">Rekap Pendapatan & Booking</x-slot>

    <div class="space-y-8">
        <!-- Header & Action with Integrated Filter Dropdowns -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm gap-6">
            <div>
                <h2 class="text-xl font-black text-blue-950">Rekapitulasi Bulanan &amp; Mingguan</h2>
                <p class="text-sm text-slate-500 font-medium">Pantau statistik pendapatan total dan volume booking servis kendaraan.</p>
            </div>
            
            <form action="{{ route('admin.recap.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                <!-- Filter Tahun -->
                <div class="relative w-full sm:w-36">
                    <select name="year" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-full pl-4 pr-10 py-2.5 text-sm font-bold text-blue-950 appearance-none focus:outline-none focus:border-red-500 cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>

                <!-- Filter Bulan -->
                <div class="relative w-full sm:w-44">
                    <select name="month" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-full pl-4 pr-10 py-2.5 text-sm font-bold text-blue-950 appearance-none focus:outline-none focus:border-red-500 cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach($monthsList as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>

                <!-- Export Excel Button -->
                <a href="{{ route('admin.recap.excel', ['year' => $selectedYear, 'month' => $selectedMonth]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700/90 text-white rounded-full text-sm font-bold shadow-md transition-all">
                    <!-- Excel SVG Icon -->
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel
                </a>
            </form>
        </div>

        @php
            $totalRevenue = $recapData->sum('revenue');
            $totalBookings = $recapData->sum('total_bookings');
            $totalCompleted = $recapData->sum('completed_bookings');
            $averageMonthlyRevenue = $recapData->count() > 0 ? $totalRevenue / $recapData->count() : 0;
        @endphp

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Total Pendapatan (Selesai)</p>
                <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Total Booking</p>
                <p class="text-2xl font-black text-blue-950">{{ $totalBookings }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Servis Berhasil Selesai</p>
                <p class="text-2xl font-black text-blue-600">{{ $totalCompleted }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-sm font-bold text-slate-500 mb-1">Rata-rata / Bulan</p>
                <p class="text-2xl font-black text-red-600">Rp {{ number_format($averageMonthlyRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        @if(!$selectedMonth)
        <!-- Charts Section (Monthly) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Revenue Chart -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-black text-blue-950 mb-4">Tren Pendapatan Bulanan (Rupiah)</h3>
                <div class="relative h-72">
                    <canvas id="revenueRecapChart"></canvas>
                </div>
            </div>

            <!-- Booking Chart -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-black text-blue-950 mb-4">Tren Volume Booking Bulanan</h3>
                <div class="relative h-72">
                    <canvas id="bookingRecapChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detail Table (Monthly) -->
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-lg font-black text-blue-950">Rincian Data Bulanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-sm">
                            <th class="py-4 px-6 font-bold text-slate-500">Bulan &amp; Tahun</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Total Booking</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-green-600">Selesai</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-orange-500">Pending</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-blue-600">Aktif (Approved/Progress)</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-red-500">Batal / Ditolak</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-right">Pendapatan Jasa &amp; Part</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recapData as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-blue-950">{{ $row['month_label'] }}</td>
                            <td class="py-4 px-6 font-medium text-slate-700">{{ $row['total_bookings'] }}</td>
                            <td class="py-4 px-6 font-bold text-green-600">{{ $row['completed_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-orange-500">{{ $row['pending_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-blue-600">{{ $row['active_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-red-500">{{ $row['cancelled_bookings'] }}</td>
                            <td class="py-4 px-6 text-right font-black text-emerald-600">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">Belum ada data rekapan bulanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Weekly Charts Section (Weekly) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Weekly Revenue Chart -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-black text-blue-950 mb-4">Tren Pendapatan Mingguan (Rupiah)</h3>
                <div class="relative h-72">
                    <canvas id="weeklyRevenueRecapChart"></canvas>
                </div>
            </div>

            <!-- Weekly Booking Chart -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-black text-blue-950 mb-4">Tren Volume Booking Mingguan</h3>
                <div class="relative h-72">
                    <canvas id="weeklyBookingRecapChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Detail Table (Weekly) -->
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-lg font-black text-blue-950">Rincian Data Mingguan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-sm">
                            <th class="py-4 px-6 font-bold text-slate-500">Rentang Tanggal (Mingguan)</th>
                            <th class="py-4 px-6 font-bold text-slate-500">Total Booking</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-green-600">Selesai</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-orange-500">Pending</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-blue-600">Aktif (Approved/Progress)</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-red-500">Batal / Ditolak</th>
                            <th class="py-4 px-6 font-bold text-slate-500 text-right">Pendapatan Jasa &amp; Part</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($weeklyRecapData as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-blue-950">{{ $row['week_label'] }}</td>
                            <td class="py-4 px-6 font-medium text-slate-700">{{ $row['total_bookings'] }}</td>
                            <td class="py-4 px-6 font-bold text-green-600">{{ $row['completed_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-orange-500">{{ $row['pending_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-blue-600">{{ $row['active_bookings'] }}</td>
                            <td class="py-4 px-6 font-medium text-red-500">{{ $row['cancelled_bookings'] }}</td>
                            <td class="py-4 px-6 text-right font-black text-emerald-600">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">Belum ada data rekapan mingguan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const months = {!! json_encode($chartMonths) !!};
                const revenues = {!! json_encode($chartRevenue) !!};
                const bookingCounts = {!! json_encode($chartBookingCount) !!};

                // Revenue Line Chart
                const revCtx = document.getElementById('revenueRecapChart');
                if (revCtx) {
                    new Chart(revCtx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: revenues,
                                borderColor: '#10b981', // emerald-500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
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
                                    ticks: {
                                        color: '#64748b',
                                        font: { weight: 'bold' },
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    },
                                    grid: { color: '#f1f5f9' }
                                },
                                x: {
                                    ticks: { color: '#64748b', font: { weight: 'bold' } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }

                // Booking Bar Chart
                const bookCtx = document.getElementById('bookingRecapChart');
                if (bookCtx) {
                    new Chart(bookCtx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Total Booking',
                                data: bookingCounts,
                                backgroundColor: '#1e293b', // blue-950 / slate-800
                                hoverBackgroundColor: '#dc2626', // red-600
                                borderRadius: 8,
                                borderWidth: 0,
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
                                    grid: { color: '#f1f5f9' }
                                },
                                x: {
                                    ticks: { color: '#64748b', font: { weight: 'bold' } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }

                const weeks = {!! json_encode($chartWeeks) !!};
                const weeklyRevenues = {!! json_encode($chartWeeklyRevenue) !!};
                const weeklyBookingCounts = {!! json_encode($chartWeeklyBookingCount) !!};

                // Weekly Revenue Line Chart
                const weekRevCtx = document.getElementById('weeklyRevenueRecapChart');
                if (weekRevCtx) {
                    new Chart(weekRevCtx, {
                        type: 'line',
                        data: {
                            labels: weeks,
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: weeklyRevenues,
                                borderColor: '#10b981', // emerald-500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
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
                                    ticks: {
                                        color: '#64748b',
                                        font: { weight: 'bold' },
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    },
                                    grid: { color: '#f1f5f9' }
                                },
                                x: {
                                    ticks: { color: '#64748b', font: { weight: 'bold' } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }

                // Weekly Booking Bar Chart
                const weekBookCtx = document.getElementById('weeklyBookingRecapChart');
                if (weekBookCtx) {
                    new Chart(weekBookCtx, {
                        type: 'bar',
                        data: {
                            labels: weeks,
                            datasets: [{
                                label: 'Total Booking',
                                data: weeklyBookingCounts,
                                backgroundColor: '#1e293b', // slate-800
                                hoverBackgroundColor: '#dc2626', // red-600
                                borderRadius: 8,
                                borderWidth: 0,
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
                                    grid: { color: '#f1f5f9' }
                                },
                                x: {
                                    ticks: { color: '#64748b', font: { weight: 'bold' } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    </x-slot>
</x-sidebar-layout>
