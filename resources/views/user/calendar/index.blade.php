<x-sidebar-layout>
    <x-slot name="title">Kalender Booking Servis</x-slot>

    <div x-data="calendarApp" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Calendar Card (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Calendar Header -->
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold shadow-sm border border-red-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-blue-950">Jadwal Booking Bulanan</h3>
                        <p class="text-xs text-slate-500 font-medium">Lihat slot terisi dan pilih hari servis terbaik.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth" class="p-2 border border-slate-200 hover:border-blue-950 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-sm font-black text-blue-950 min-w-[120px] text-center" x-text="monthName"></span>
                    <button @click="nextMonth" class="p-2 border border-slate-200 hover:border-blue-950 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Body -->
            <div class="p-6">
                <!-- Legend -->
                <div class="mb-5 flex flex-wrap items-center justify-between gap-y-3 gap-x-6 bg-slate-50 px-5 py-3.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-500 shadow-xs">
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-emerald-50 border border-emerald-300 block shrink-0 shadow-xs"></span>
                            <span class="text-slate-700">Bisa Booking</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-rose-50 border border-rose-300 block shrink-0 shadow-xs"></span>
                            <span class="text-slate-700">Kuota Penuh</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-slate-100 border border-slate-300 block shrink-0 shadow-xs"></span>
                            <span class="text-slate-700">Hari Libur</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-slate-50 border border-slate-200 block shrink-0 opacity-60 shadow-xs"></span>
                            <span class="text-slate-500">Belum Buka</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 text-red-650 bg-red-50 border border-red-200 px-2.5 py-1 rounded-xl shadow-xs text-[10px] font-black uppercase tracking-wider shrink-0">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span>Booking Anda</span>
                    </div>
                </div>

                <!-- Weekdays Grid -->
                <div class="grid grid-cols-7 gap-2 mb-4 text-center">
                    <template x-for="day in weekdays" :key="day">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest py-2 bg-slate-50/50 rounded-lg" x-text="day"></span>
                    </template>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 gap-1.5 sm:gap-3">
                    <template x-for="(cell, index) in days" :key="index">
                        <div @click="selectDate(cell.dateStr)" 
                             class="aspect-square rounded-[20px] p-2.5 sm:p-3.5 flex flex-col justify-between transition-all duration-300 cursor-pointer relative group border shadow-sm text-left"
                             :class="{
                                 'border-slate-100 bg-slate-50/20 opacity-30 pointer-events-none': !cell.isCurrentMonth,
                                 
                                 // Tutup (Belum ada jadwal)
                                 'border-slate-150 bg-slate-50/30 text-slate-400': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !getSchedule(cell.dateStr),
                                 
                                 // Libur
                                 'border-slate-300 bg-slate-100 text-slate-500': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && getSchedule(cell.dateStr).is_holiday,
                                 
                                 // Penuh
                                 'border-rose-300 bg-rose-50/40 text-slate-700 hover:border-rose-450 hover:bg-rose-50/70': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && getSchedule(cell.dateStr).is_full,
                                 
                                 // Available
                                 'border-emerald-300 bg-emerald-50/30 text-slate-700 hover:border-emerald-450 hover:bg-emerald-50/60 hover:shadow-md': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && !getSchedule(cell.dateStr).is_full,
                                 
                                 // Selected
                                 'border-blue-950 bg-blue-950 text-white shadow-lg scale-[1.02]': selectedDateStr === cell.dateStr,
                                 
                                 // Today Border outline
                                 'ring-2 ring-red-500/20 border-red-500': isToday(cell.dateStr) && selectedDateStr !== cell.dateStr
                             }">
                            
                            <!-- Top Row: Date Num & Own Booking Indicator -->
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-sm font-black transition-all"
                                          :class="{
                                              'text-blue-950': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday,
                                              'text-slate-400': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && (!getSchedule(cell.dateStr) || getSchedule(cell.dateStr).is_holiday),
                                              'bg-red-600 text-white shadow-xs': isToday(cell.dateStr) && selectedDateStr !== cell.dateStr,
                                              'bg-white text-blue-950': isToday(cell.dateStr) && selectedDateStr === cell.dateStr,
                                              'text-white': selectedDateStr === cell.dateStr && !isToday(cell.dateStr)
                                          }" x-text="cell.dayNum"></span>
                                    
                                    <!-- Star Indicator for Own Booking -->
                                    <template x-if="hasOwnBooking(cell.dateStr)">
                                        <svg class="w-3.5 h-3.5 text-amber-500 fill-current shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </template>
                                </div>
                            </div>

                            <!-- Bottom Row: Bookings Count Circle -->
                            <div class="flex justify-end items-center mt-auto">
                                <template x-if="cell.isCurrentMonth && getBookingsForDate(cell.dateStr).length > 0">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black border shadow-xs transition-all shrink-0"
                                          :class="{
                                              'bg-white/20 text-white border-white/10': selectedDateStr === cell.dateStr,
                                              'bg-blue-50 text-blue-700 border-blue-150': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length <= 2,
                                              'bg-amber-50 text-amber-700 border-amber-150': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length > 2 && getBookingsForDate(cell.dateStr).length <= 4,
                                              'bg-red-550 text-white border-red-600': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length > 4
                                          }"
                                          x-text="getBookingsForDate(cell.dateStr).length"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>


            </div>
        </div>

        <!-- Right: Detail Panel (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-6 sticky top-24">
            <!-- Header Info -->
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 shrink-0">
                    <h3 class="text-base font-black text-blue-950">Detail Antrean Harian</h3>
                </div>
                
                <div class="p-6">
                    <!-- Empty State -->
                    <div x-show="!selectedDateStr" class="text-center py-16 space-y-4">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-blue-950 text-sm">Pilih Tanggal</p>
                            <p class="text-xs text-slate-500 font-medium max-w-[200px] mx-auto mt-1 leading-relaxed">Silakan klik salah satu tanggal di kalender untuk melihat daftar booking.</p>
                        </div>
                    </div>

                    <!-- Selected Date State -->
                    <div x-show="selectedDateStr" class="space-y-6" style="display: none;">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Terpilih</p>
                            <h4 class="text-base font-black text-blue-950" x-text="selectedDateFormatted"></h4>
                            
                            <!-- Dynamic Schedule Box -->
                            <div class="mt-3 p-4 rounded-xl border text-xs font-semibold leading-relaxed"
                                 :class="{
                                     'bg-slate-50 border-slate-200 text-slate-600': !getSchedule(selectedDateStr) || getSchedule(selectedDateStr).is_holiday,
                                     'bg-red-50/50 border-red-150 text-red-700': getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && getSchedule(selectedDateStr).is_full,
                                     'bg-green-50/40 border-green-150 text-green-850': getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && !getSchedule(selectedDateStr).is_full
                                 }">
                                <template x-if="!getSchedule(selectedDateStr)">
                                    <div>
                                        <p class="font-extrabold uppercase tracking-wider mb-1 text-slate-500">Bengkel Tutup</p>
                                        <p class="font-medium text-slate-500">Jadwal operasional praktikum belum dibuka untuk tanggal ini.</p>
                                    </div>
                                </template>
                                <template x-if="getSchedule(selectedDateStr) && getSchedule(selectedDateStr).is_holiday">
                                    <div>
                                        <p class="font-extrabold uppercase tracking-wider mb-1 text-red-500">Libur Operasional</p>
                                        <p class="font-medium text-slate-500">Bengkel tutup karena libur sekolah atau libur nasional.</p>
                                    </div>
                                </template>
                                <template x-if="getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday">
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 uppercase tracking-widest text-[9px] font-bold">Status:</span>
                                            <span class="font-black uppercase tracking-wider text-[10px]" :class="getSchedule(selectedDateStr).is_full ? 'text-red-600' : 'text-green-700'" x-text="getSchedule(selectedDateStr).is_full ? 'Kuota Penuh' : 'Buka / Menerima Booking'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 uppercase tracking-widest text-[9px] font-bold">Jam Praktik:</span>
                                            <span class="font-bold text-blue-950" x-text="getSchedule(selectedDateStr).jam_buka + ' - ' + getSchedule(selectedDateStr).jam_tutup + ' WITA'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 uppercase tracking-widest text-[9px] font-bold">Sisa Kuota:</span>
                                            <span class="font-bold text-blue-950" x-text="getSchedule(selectedDateStr).sisa_kuota + ' mnt (total: ' + getSchedule(selectedDateStr).kapasitas_menit + ' mnt)'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Bookings list -->
                        <div class="space-y-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Daftar Antrean (<span x-text="getBookingsForDate(selectedDateStr).length"></span>)</p>
                            
                            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(b, idx) in getBookingsForDate(selectedDateStr)" :key="b.id">
                                    <div class="border rounded-[20px] p-5 transition-all relative overflow-hidden bg-white"
                                         :class="b.is_own ? 'border-red-500/30 bg-red-50/10 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 shadow-sm'">
                                        
                                        <!-- Decorative colored bar on left -->
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5"
                                             :class="b.is_own ? 'bg-red-500' : 'bg-slate-200'"></div>
                                        
                                        <div class="pl-2">
                                            <div class="flex justify-between items-start gap-2 mb-3">
                                                <div>
                                                    <!-- Own Booking Label -->
                                                    <template x-if="b.is_own">
                                                        <span class="inline-block bg-red-600 text-white text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider mb-1.5">Booking Anda</span>
                                                    </template>
                                                    <h5 class="font-extrabold text-sm text-blue-950" x-text="b.kendaraan"></h5>
                                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-0.5 font-mono" x-text="b.plat_nomor"></p>
                                                </div>
                                                <!-- Status Badge -->
                                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest whitespace-nowrap"
                                                      :class="{
                                                          'bg-orange-50 text-orange-600 border border-orange-100': b.status === 'pending',
                                                          'bg-blue-50 text-blue-600 border border-blue-100': b.status === 'approved',
                                                          'bg-indigo-50 text-indigo-600 border border-indigo-100': b.status === 'in_progress',
                                                          'bg-green-50 text-green-600 border border-green-100': b.status === 'completed'
                                                      }"
                                                      x-text="b.status === 'pending' ? 'Pending' : b.status === 'approved' ? 'Disetujui' : b.status === 'in_progress' ? 'Servis' : 'Selesai'"></span>
                                            </div>
                                            <div class="text-xs text-slate-500 space-y-2 border-t border-slate-100 pt-3">
                                                <div class="flex justify-between">
                                                    <span class="text-slate-400 font-bold">Pelanggan:</span>
                                                    <span class="font-bold text-slate-700" x-text="b.user_name"></span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <span class="text-slate-400 font-bold shrink-0">Layanan:</span>
                                                    <span class="font-bold text-slate-700 text-right ml-4" x-text="b.paket"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="getBookingsForDate(selectedDateStr).length === 0">
                                    <div class="text-center py-12 text-slate-400 font-bold text-xs uppercase tracking-widest bg-slate-50 rounded-[20px] border border-dashed border-slate-200">
                                        Belum Ada Antrean
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4 border-t border-slate-100">
                            <!-- Disable booking if date is not open / libur / penuh -->
                            <template x-if="getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && !getSchedule(selectedDateStr).is_full">
                                <a :href="'{{ route('user.dashboard') }}?tanggal=' + selectedDateStr" class="block w-full py-3.5 bg-red-600 hover:bg-red-700/90 text-white rounded-xl font-bold text-sm text-center shadow-lg transition-all">
                                    Booking Servis Hari Ini
                                </a>
                            </template>
                            <template x-if="!getSchedule(selectedDateStr) || getSchedule(selectedDateStr).is_holiday || getSchedule(selectedDateStr).is_full">
                                <button disabled class="block w-full py-3.5 bg-slate-200 text-slate-400 rounded-xl font-bold text-sm text-center cursor-not-allowed">
                                    Booking Tidak Tersedia
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('calendarApp', () => ({
                    currentDate: new Date(),
                    selectedDateStr: '',
                    bookings: {!! json_encode($bookingsJson) !!},
                    schedules: {!! json_encode($schedulesJson ?? []) !!},
                    days: [],
                    weekdays: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],

                    init() {
                        this.generateCalendar();
                        // Pre-select today if available
                        const todayStr = this.formatDateStr(new Date());
                        this.selectedDateStr = todayStr;
                    },

                    prevMonth() {
                        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                        this.generateCalendar();
                    },

                    nextMonth() {
                        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                        this.generateCalendar();
                    },

                    get monthName() {
                        return this.currentDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                    },

                    generateCalendar() {
                        const year = this.currentDate.getFullYear();
                        const month = this.currentDate.getMonth();
                        
                        // First day of the month
                        const firstDayOfMonth = new Date(year, month, 1);
                        // Day of the week of the first day (0 = Sun, 1 = Mon, ..., 6 = Sat)
                        let startDay = firstDayOfMonth.getDay();
                        // Shift so Mon is 0, Sun is 6
                        startDay = startDay === 0 ? 6 : startDay - 1;

                        // Last day of the month
                        const lastDayOfMonth = new Date(year, month + 1, 0);
                        const totalDays = lastDayOfMonth.getDate();

                        const daysArray = [];

                        // Pad previous month's days
                        const prevMonthLastDay = new Date(year, month, 0).getDate();
                        for (let i = startDay - 1; i >= 0; i--) {
                            const date = new Date(year, month - 1, prevMonthLastDay - i);
                            daysArray.push({
                                date: date,
                                dayNum: date.getDate(),
                                isCurrentMonth: false,
                                dateStr: this.formatDateStr(date),
                            });
                        }

                        // Current month's days
                        for (let i = 1; i <= totalDays; i++) {
                            const date = new Date(year, month, i);
                            daysArray.push({
                                date: date,
                                dayNum: i,
                                isCurrentMonth: true,
                                dateStr: this.formatDateStr(date),
                            });
                        }

                        // Pad next month's days to make it 42 cells (6 rows * 7 columns)
                        const totalCells = 42;
                        const remainingCells = totalCells - daysArray.length;
                        for (let i = 1; i <= remainingCells; i++) {
                            const date = new Date(year, month + 1, i);
                            daysArray.push({
                                date: date,
                                dayNum: i,
                                isCurrentMonth: false,
                                dateStr: this.formatDateStr(date),
                            });
                        }

                        this.days = daysArray;
                    },

                    formatDateStr(date) {
                        const y = date.getFullYear();
                        const m = String(date.getMonth() + 1).padStart(2, '0');
                        const d = String(date.getDate()).padStart(2, '0');
                        return `${y}-${m}-${d}`;
                    },

                    isToday(dateStr) {
                        const today = new Date();
                        return this.formatDateStr(today) === dateStr;
                    },

                    getSchedule(dateStr) {
                        return this.schedules[dateStr] || null;
                    },

                    getBookingsForDate(dateStr) {
                        return this.bookings.filter(b => b.tanggal === dateStr);
                    },

                    hasOwnBooking(dateStr) {
                        return this.bookings.some(b => b.tanggal === dateStr && b.is_own);
                    },

                    selectDate(dateStr) {
                        this.selectedDateStr = dateStr;
                    },

                    get selectedDateFormatted() {
                        if (!this.selectedDateStr) return '';
                        const parts = this.selectedDateStr.split('-');
                        const date = new Date(parts[0], parts[1] - 1, parts[2]);
                        return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    }
                }));
            });
        </script>
    </x-slot>
</x-sidebar-layout>
