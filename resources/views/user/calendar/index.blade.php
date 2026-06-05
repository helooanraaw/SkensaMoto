<x-sidebar-layout>
    <x-slot name="title">Kalender Booking Servis</x-slot>

    <div x-data="calendarApp" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Calendar Card (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
            <!-- Calendar Header -->
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold border border-red-100 shadow-xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Jadwal Booking Bulanan</h3>
                        <p class="text-xs text-slate-500 font-medium">Lihat slot terisi dan pilih hari servis terbaik.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth" class="p-2 border border-slate-200 hover:border-slate-800 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-sm font-bold text-slate-800 min-w-[120px] text-center" x-text="monthName"></span>
                    <button @click="nextMonth" class="p-2 border border-slate-200 hover:border-slate-800 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Body -->
            <div class="p-6">
                <!-- Legend -->
                <div class="mb-5 flex flex-wrap items-center justify-between gap-y-3 gap-x-6 bg-slate-50 px-5 py-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-650">
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-emerald-50/40 border border-emerald-500/30 block shrink-0 shadow-xs"></span>
                            <span class="text-emerald-800">Bisa Booking</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-rose-50/50 border border-rose-500/30 block shrink-0 shadow-xs"></span>
                            <span class="text-rose-800">Kuota Penuh</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-slate-100/70 border border-slate-400/30 block shrink-0 shadow-xs"></span>
                            <span class="text-slate-700">Hari Libur</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-slate-50/30 border border-slate-200/40 block shrink-0 opacity-60 shadow-xs"></span>
                            <span class="text-slate-400">Belum Buka</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-xs font-semibold">Booking Anda</span>
                    </div>
                </div>

                <!-- Weekdays Grid -->
                <div class="grid grid-cols-7 gap-2 mb-3 text-center">
                    <template x-for="day in weekdays" :key="day">
                        <span class="text-xs font-bold text-slate-550 uppercase tracking-wider py-1.5" x-text="day"></span>
                    </template>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 gap-1.5 sm:gap-2.5">
                    <template x-for="(cell, index) in days" :key="index">
                        <div @click="selectDate(cell.dateStr)" 
                             class="aspect-square rounded-xl p-2 sm:p-3 flex flex-col justify-between transition-all duration-200 cursor-pointer relative group border text-left shadow-xs hover:scale-[1.02]"
                             :class="{
                                 'border-slate-100 bg-slate-50/10 opacity-30 pointer-events-none': !cell.isCurrentMonth,
                                 
                                 // Tutup (Belum Buka)
                                 'border-slate-200/40 bg-slate-50/30 text-slate-400 opacity-65': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !getSchedule(cell.dateStr),
                                 
                                 // Libur
                                 'border-slate-400/30 bg-slate-100/60 text-slate-550 hover:bg-slate-100 hover:border-slate-400/60': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && getSchedule(cell.dateStr).is_holiday,
                                 
                                 // Penuh
                                 'border-rose-500/30 bg-rose-50/40 text-slate-700 hover:bg-rose-50/60 hover:border-rose-500/60': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && getSchedule(cell.dateStr).is_full,
                                 
                                 // Available
                                 'border-emerald-500/30 bg-white text-emerald-700 hover:border-emerald-500/60 hover:bg-emerald-50/20': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && !getSchedule(cell.dateStr).is_full,
                                 
                                 // Selected
                                 'border-slate-800 bg-slate-800 text-white shadow-md transform scale-[1.03] z-10': selectedDateStr === cell.dateStr
                             }">
                            
                            <!-- Top Row: Date Num & Own Booking Indicator -->
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-1">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-sm font-extrabold transition-colors"
                                          :class="{
                                              // Buka (Bisa Booking)
                                              'text-emerald-700 font-extrabold': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && !getSchedule(cell.dateStr).is_full,
                                              
                                              // Penuh
                                              'text-rose-600 font-extrabold': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && getSchedule(cell.dateStr) && !getSchedule(cell.dateStr).is_holiday && getSchedule(cell.dateStr).is_full,
                                              
                                              // Libur
                                              'text-slate-500 font-extrabold': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && getSchedule(cell.dateStr) && getSchedule(cell.dateStr).is_holiday,
                                              
                                              // Tutup (Belum Buka)
                                              'text-slate-400 font-extrabold': cell.isCurrentMonth && selectedDateStr !== cell.dateStr && !isToday(cell.dateStr) && !getSchedule(cell.dateStr),
                                              
                                              // Today States
                                              'bg-red-600 text-white font-extrabold': isToday(cell.dateStr) && selectedDateStr !== cell.dateStr,
                                              'bg-white text-slate-900 font-extrabold': isToday(cell.dateStr) && selectedDateStr === cell.dateStr,
                                              
                                              // Selected (Non-today)
                                              'text-white font-extrabold': selectedDateStr === cell.dateStr && !isToday(cell.dateStr)
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
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0"
                                          :class="{
                                              'bg-white/20 text-white': selectedDateStr === cell.dateStr,
                                              'bg-slate-100 text-slate-600': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length <= 2,
                                              'bg-amber-100 text-amber-800': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length > 2 && getBookingsForDate(cell.dateStr).length <= 4,
                                              'bg-rose-100 text-rose-800': selectedDateStr !== cell.dateStr && getBookingsForDate(cell.dateStr).length > 4
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
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 shrink-0">
                    <h3 class="text-sm font-bold text-slate-800">Detail Antrean Harian</h3>
                </div>
                
                <div class="p-6">
                    <!-- Empty State -->
                    <div x-show="!selectedDateStr" class="text-center py-16 space-y-4">
                        <div class="w-12 h-12 bg-slate-50 text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Pilih Tanggal</p>
                            <p class="text-xs text-slate-550 max-w-[220px] mx-auto mt-1 leading-relaxed">Silakan klik salah satu tanggal di kalender untuk melihat daftar booking.</p>
                        </div>
                    </div>

                    <!-- Selected Date State -->
                    <div x-show="selectedDateStr" class="space-y-5" style="display: none;">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Terpilih</p>
                            <h4 class="text-sm font-bold text-slate-800" x-text="selectedDateFormatted"></h4>
                            
                            <!-- Dynamic Schedule Box -->
                            <div class="mt-3 p-3.5 rounded-xl border text-xs font-medium leading-relaxed"
                                 :class="{
                                     'bg-slate-50 border-slate-200 text-slate-650': !getSchedule(selectedDateStr) || getSchedule(selectedDateStr).is_holiday,
                                     'bg-red-50/40 border-red-150 text-red-750': getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && getSchedule(selectedDateStr).is_full,
                                     'bg-emerald-50/30 border-emerald-150 text-emerald-800': getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && !getSchedule(selectedDateStr).is_full
                                 }">
                                <template x-if="!getSchedule(selectedDateStr)">
                                    <div>
                                        <p class="font-bold uppercase tracking-wider mb-0.5 text-slate-500">Bengkel Tutup</p>
                                        <p class="text-slate-500 text-[11px]">Jadwal operasional praktikum belum dibuka untuk tanggal ini.</p>
                                    </div>
                                </template>
                                <template x-if="getSchedule(selectedDateStr) && getSchedule(selectedDateStr).is_holiday">
                                    <div>
                                        <p class="font-bold uppercase tracking-wider mb-0.5 text-red-650">Libur Operasional</p>
                                        <p class="text-slate-500 text-[11px]">Bengkel tutup karena libur sekolah atau libur nasional.</p>
                                    </div>
                                </template>
                                <template x-if="getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday">
                                    <div class="space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-[10px] font-semibold">Status:</span>
                                            <span class="font-bold uppercase text-[10px]" :class="getSchedule(selectedDateStr).is_full ? 'text-red-600' : 'text-emerald-700'" x-text="getSchedule(selectedDateStr).is_full ? 'Kuota Penuh' : 'Buka / Menerima Booking'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-[10px] font-semibold">Jam Praktik:</span>
                                            <span class="font-bold text-slate-800" x-text="getSchedule(selectedDateStr).jam_buka + ' - ' + getSchedule(selectedDateStr).jam_tutup + ' WITA'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-[10px] font-semibold">Sisa Kuota:</span>
                                            <span class="font-bold text-slate-800" x-text="getSchedule(selectedDateStr).sisa_kuota + ' mnt (total: ' + getSchedule(selectedDateStr).kapasitas_menit + ' mnt)'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Bookings list -->
                        <div class="space-y-3">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Daftar Antrean (<span x-text="getBookingsForDate(selectedDateStr).length"></span>)</p>
                            
                            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                                <template x-for="(b, idx) in getBookingsForDate(selectedDateStr)" :key="b.id">
                                    <div class="border rounded-xl p-4 transition-colors relative overflow-hidden bg-white"
                                         :class="b.is_own ? 'border-red-200 bg-red-50/10' : 'border-slate-200 bg-white hover:border-slate-350'">
                                        
                                        <!-- Decorative colored bar on left -->
                                        <div class="absolute left-0 top-0 bottom-0 w-1"
                                             :class="b.is_own ? 'bg-red-550' : 'bg-slate-200'"></div>
                                        
                                        <div class="pl-2">
                                            <div class="flex justify-between items-start gap-2 mb-2">
                                                <div>
                                                    <!-- Own Booking Label -->
                                                    <template x-if="b.is_own">
                                                        <span class="inline-block bg-red-600 text-white text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider mb-1">Booking Anda</span>
                                                    </template>
                                                    <h5 class="font-bold text-sm text-slate-800" x-text="b.kendaraan"></h5>
                                                    <p class="text-xs text-slate-450 font-semibold uppercase tracking-wider mt-0.5 font-mono" x-text="b.plat_nomor"></p>
                                                </div>
                                                <!-- Status Badge -->
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider whitespace-nowrap"
                                                      :class="{
                                                          'bg-orange-50 text-orange-600 border border-orange-100': b.status === 'pending',
                                                          'bg-blue-50 text-blue-600 border border-blue-100': b.status === 'approved',
                                                          'bg-indigo-50 text-indigo-600 border border-indigo-100': b.status === 'in_progress',
                                                          'bg-green-50 text-green-600 border border-green-100': b.status === 'completed'
                                                      }"
                                                      x-text="b.status === 'pending' ? 'Pending' : b.status === 'approved' ? 'Disetujui' : b.status === 'in_progress' ? 'Servis' : 'Selesai'"></span>
                                            </div>
                                            <div class="text-xs text-slate-500 space-y-1 border-t border-slate-100 pt-2">
                                                <div class="flex justify-between">
                                                    <span class="text-slate-400 font-medium">Pelanggan:</span>
                                                    <span class="font-semibold text-slate-700" x-text="b.user_name"></span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <span class="text-slate-400 font-medium shrink-0">Layanan:</span>
                                                    <span class="font-semibold text-slate-700 text-right ml-4" x-text="b.paket"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="getBookingsForDate(selectedDateStr).length === 0">
                                    <div class="text-center py-10 text-slate-400 font-bold text-xs uppercase tracking-wider bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                        Belum Ada Antrean
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-3 border-t border-slate-100">
                            <!-- Disable booking if date is not open / libur / penuh -->
                            <template x-if="getSchedule(selectedDateStr) && !getSchedule(selectedDateStr).is_holiday && !getSchedule(selectedDateStr).is_full">
                                <a :href="'{{ route('user.dashboard') }}?tanggal=' + selectedDateStr" class="block w-full py-3 bg-red-650 hover:bg-red-700 text-white rounded-xl font-bold text-sm text-center transition-colors">
                                    Booking Servis Hari Ini
                                </a>
                            </template>
                            <template x-if="!getSchedule(selectedDateStr) || getSchedule(selectedDateStr).is_holiday || getSchedule(selectedDateStr).is_full">
                                <button disabled class="block w-full py-3 bg-slate-100 text-slate-400 rounded-xl font-bold text-sm text-center cursor-not-allowed">
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
                    monthName: '',
                    selectedDateFormatted: '',
                    bookings: {!! json_encode($bookingsJson) !!},
                    schedules: {!! json_encode($schedulesJson ?? []) !!},
                    days: [],
                    weekdays: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],

                    init() {
                        this.updateMonthName();
                        this.generateCalendar();
                        // Pre-select today if available
                        const todayStr = this.formatDateStr(new Date());
                        this.selectDate(todayStr);
                    },

                    prevMonth() {
                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                        this.updateMonthName();
                        this.generateCalendar();
                    },

                    nextMonth() {
                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                        this.updateMonthName();
                        this.generateCalendar();
                    },

                    updateMonthName() {
                        this.monthName = this.currentDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
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
                        this.updateSelectedDateFormatted();
                    },

                    updateSelectedDateFormatted() {
                        if (!this.selectedDateStr) {
                            this.selectedDateFormatted = '';
                            return;
                        }
                        const parts = this.selectedDateStr.split('-');
                        const date = new Date(parts[0], parts[1] - 1, parts[2]);
                        this.selectedDateFormatted = date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    }
                }));
            });
        </script>
    </x-slot>
</x-sidebar-layout>
