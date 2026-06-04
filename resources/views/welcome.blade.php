<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkensaMoto - Skensa Motor SMK Negeri 1 Denpasar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Menggunakan Font Urbanist -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/MotoSkensaLogo1.png') }}" type="image/png">
    
    <style>
        body { font-family: 'Urbanist', sans-serif; }
        .honda-red { color: #E3000F; }
        .bg-honda-red { background-color: #E3000F; }
        .border-honda-red { border-color: #E3000F; }
        .hover-bg-honda-red-dark:hover { background-color: #cc000e; }

        /* Custom Ultra Smooth Easing */
        .nav-transition {
            transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: max-width, width, background-color, border-radius, box-shadow, backdrop-filter, border-color;
        }

    </style>
</head>
<body class="bg-white text-blue-950 antialiased selection:bg-red-100 selection:text-red-900">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full top-0 z-50 pt-8 group/nav nav-transition">
        <div id="nav-container" class="w-full max-w-full mx-auto nav-transition group-[.is-scrolled]/nav:bg-white/95 group-[.is-scrolled]/nav:backdrop-blur-md group-[.is-scrolled]/nav:shadow-2xl group-[.is-scrolled]/nav:rounded-full group-[.is-scrolled]/nav:max-w-5xl  group-[.is-scrolled]/nav:border-white/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20 px-4 nav-transition">
                    <!-- Logo (Kiri) -->
                    <div class="flex items-center w-1/4">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-10 w-auto drop-shadow-md nav-transition group-[.is-scrolled]/nav:drop-shadow-none" id="nav-logo" />
                            <div class="flex flex-col">
                                <span class="font-extrabold text-2xl leading-tight tracking-tight text-blue-950 nav-transition group-[.is-scrolled]/nav:text-blue-950" id="nav-title">Skensa<span class="text-red-500 group-[.is-scrolled]/nav:text-red-600 nav-transition" id="nav-title-moto">Moto</span></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu (Tengah) -->
                    <div class="hidden lg:flex items-center justify-center w-2/4">
                        <div class="flex items-center gap-8 px-2">
                            <a href="#" class="nav-link text-blue-950/90 hover:text-red-600 font-bold nav-transition group-[.is-scrolled]/nav:text-blue-950/80 group-[.is-scrolled]/nav:hover:text-red-600">Beranda</a>
                            <a href="#services" class="nav-link text-blue-950/90 hover:text-red-600 font-bold nav-transition group-[.is-scrolled]/nav:text-blue-950/80 group-[.is-scrolled]/nav:hover:text-red-600">Layanan Standar</a>
                            <a href="#schedule" class="nav-link text-white/90 hover:text-white font-bold nav-transition group-[.is-scrolled]/nav:text-blue-950/80 group-[.is-scrolled]/nav:hover:text-red-600">Jadwal Booking</a>
                        </div>
                    </div>

                    <!-- Tombol (Kanan) -->
                    <div class="hidden lg:flex items-center justify-end w-1/4">
                        <a href="/login" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-xl nav-transition">Booking Antrian</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden min-h-screen flex items-center">
        <!-- Full Bleed Image Background (Right 60%) -->
        <div class="absolute top-0 right-0 w-full lg:w-[55%] h-full z-0">
            <!-- Background Image -->
            <img src="{{ asset('images/LandingPageTSM.jpg') }}" alt="Motorcycle Workshop" class="absolute inset-0 w-full h-full object-cover object-center" />
            
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-slate-950/10"></div>
            
            <!-- Gradient Fade-in dari Kiri -->
            <div class="absolute inset-y-0 left-0 w-1/4 bg-gradient-to-r from-white to-transparent"></div>
            
            <!-- Gradient Fade-in dari Bawah khusus mobile -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-white to-transparent lg:hidden"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 w-full">
            <div class="relative py-32 lg:py-48 px-4 sm:px-6 lg:px-8 flex flex-col items-start justify-center">
                <div class="w-full lg:w-1/2 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-700 text-sm font-bold mb-6 border border-red-100 shadow-sm relative z-20">
                        <span class="flex h-2 w-2 rounded-full bg-red-600"></span>
                        Teknik Sepeda Motor (TSM) SMKN 1 Denpasar
                    </div>  
                    <h1 class="text-4xl tracking-tight font-black text-blue-950 sm:text-5xl lg:text-6xl mb-6 leading-tight relative z-20 drop-shadow-sm">
                        Booking Service Motor<br>
                        <span class="text-red-600 drop-shadow-md">Standar Dealer Resmi</span>
                    </h1>
                    <p class="text-lg text-blue-950 lg:text-blue-950 mb-8 leading-relaxed font-bold lg:font-medium max-w-lg relative z-20">
                        Layanan servis profesional seperti di Dealer Resmi, langsung di SMK Negeri 1 Denpasar. Dikerjakan oleh siswa-siswa terbaik Teknik Sepeda Motor di bawah pengawasan ketat instruktur tersertifikasi industri.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 items-center relative z-20">
                        <a href="/login" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-xl font-bold text-lg transition-all flex items-center justify-center gap-2 w-full sm:w-auto ">
                            Mulai Booking
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Live Schedule Board -->
    <div id="schedule" class="py-20 bg-gray-50/50 border-b border-slate-100" x-data="{ showModal: false, activeBookings: [], activeDate: '' }" x-effect="showModal ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-12 text-center lg:text-left">
                <span class="text-red-600 font-bold uppercase tracking-wider text-xs block mb-2">Kuota & Antrean</span>
                <h2 class="text-3xl font-extrabold text-blue-950">Jadwal Antrian Bengkel</h2>
                <p class="mt-2 text-slate-600 font-medium max-w-2xl text-sm leading-relaxed">
                    Untuk menjaga kualitas servis, kami membatasi jumlah motor setiap harinya.
                    Silakan pilih hari yang tersedia (Warna Hijau) untuk melihat antrean atau melakukan booking tanpa antre lama di lokasi.
                </p>
            </div>

            <!-- Main Grid layout for steps and schedule -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Left Column: Booking Steps (Stacked Vertically) -->
                <div class="lg:col-span-5 space-y-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Panduan Antrean</p>
                    <h3 class="text-base font-black text-blue-950 mb-4 border-b border-slate-200/60 pb-2">3 Langkah Mudah Booking</h3>
                    
                    <!-- Step 1 -->
                    <div class="bg-white border border-slate-100 rounded-[20px] p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all duration-300 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 font-black text-lg flex items-center justify-center shrink-0 border border-red-100">1</div>
                        <div>
                            <h4 class="font-extrabold text-sm text-blue-950">Pilih Tanggal</h4>
                            <p class="text-xs text-slate-500 font-semibold mt-1 leading-normal">Cek ketersediaan jadwal pada hari-hari di sebelah kanan. Pilih tanggal yang masih memiliki sisa kuota.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white border border-slate-100 rounded-[20px] p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all duration-300 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 font-black text-lg flex items-center justify-center shrink-0 border border-red-100">2</div>
                        <div>
                            <h4 class="font-extrabold text-sm text-blue-950">Isi Keluhan Motor</h4>
                            <p class="text-xs text-slate-500 font-semibold mt-1 leading-normal">Setelah masuk ke akun Anda, daftarkan motor Honda Anda dan tulis keluhan mesin secara detail.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white border border-slate-100 rounded-[20px] p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all duration-300 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 font-black text-lg flex items-center justify-center shrink-0 border border-red-100">3</div>
                        <div>
                            <h4 class="font-extrabold text-sm text-blue-950">Bawa Sesuai Jadwal</h4>
                            <p class="text-xs text-slate-500 font-semibold mt-1 leading-normal">Bawa motor ke bengkel MotoSkensa tepat waktu. Motor Anda akan langsung masuk area pengerjaan.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Daily Schedules (Stacked Vertically) -->
                <div class="lg:col-span-7 space-y-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ketersediaan Jadwal</p>
                    <h3 class="text-base font-black text-blue-950 mb-4 border-b border-slate-200/60 pb-2">Kuota Servis Terbuka</h3>
                    
                    <div class="space-y-4">
                        @forelse($schedules as $key => $schedule)
                            @php
                                $dateObj = \Carbon\Carbon::parse($schedule->tanggal)->locale('id');
                                $dateStr = $dateObj->format('Y-m-d');
                                $isToday   = $dateObj->isToday();
                                $sisaKuotaMenit = $schedule->kapasitas_menit - $schedule->terpakai_menit;
                                $isFull    = $sisaKuotaMenit <= 0;
                                $isHoliday = $schedule->kapasitas_menit == 0;
                            @endphp

                            @if($isHoliday)
                                <div class="bg-white border border-slate-150 rounded-[20px] p-5 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="bg-slate-100 text-slate-500 rounded-xl p-2.5 text-center min-w-[75px] border border-slate-200 shadow-inner">
                                            <p class="text-[8px] font-black uppercase tracking-widest leading-none">{{ $dateObj->translatedFormat('l') }}</p>
                                            <p class="text-lg font-black leading-none mt-1.5">{{ $dateObj->format('j M') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-sm text-slate-400">Libur Operasional</h4>
                                            <p class="text-xs text-slate-400 font-semibold mt-0.5">Bengkel tutup / libur sekolah.</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 bg-slate-50 text-slate-400 text-xs font-black px-4 py-2 border border-slate-200 rounded-xl uppercase tracking-wider">TUTUP</div>
                                </div>

                            @elseif($isFull)
                                <div class="bg-white border {{ $isToday ? 'border-red-400 ring-2 ring-red-500/10' : 'border-slate-150' }} rounded-[20px] p-5 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="bg-red-50 text-red-600 rounded-xl p-2.5 text-center min-w-[75px] border border-red-100 shadow-inner">
                                            <p class="text-[8px] font-black uppercase tracking-widest leading-none">{{ $isToday ? 'HARI INI' : $dateObj->translatedFormat('l') }}</p>
                                            <p class="text-lg font-black leading-none mt-1.5">{{ $dateObj->format('j M') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-sm text-blue-950">Kuota Penuh</h4>
                                            <p class="text-xs text-slate-500 font-semibold mt-0.5">Jam Buka: {{ substr($schedule->jam_buka,0,5) }} – {{ substr($schedule->jam_tutup,0,5) }} WITA</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 bg-red-50 text-red-600 text-xs font-black px-4 py-2 border border-red-200 rounded-xl uppercase tracking-wider">Penuh</div>
                                </div>

                            @else
                                <div @click="showModal = true; activeDate = '{{ $dateObj->translatedFormat('d F Y') }}'; activeBookings = window.publicBookings['{{ $dateStr }}'] || []" 
                                     class="w-full bg-white border {{ $isToday ? 'border-red-400 ring-2 ring-red-500/10' : 'border-slate-150' }} hover:border-green-400 hover:shadow-md rounded-[20px] p-5 flex flex-col sm:flex-row justify-between items-center gap-4 transition-all duration-300 group text-left cursor-pointer shadow-sm">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="bg-green-50 text-green-600 rounded-xl p-2.5 text-center min-w-[75px] border border-green-100 group-hover:bg-green-100 transition shadow-inner">
                                            <p class="text-[8px] font-black uppercase tracking-widest leading-none">{{ $isToday ? 'HARI INI' : $dateObj->translatedFormat('l') }}</p>
                                            <p class="text-lg font-black leading-none mt-1.5">{{ $dateObj->format('j M') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-sm text-blue-950 group-hover:text-green-600 transition-colors">Bengkel Dibuka</h4>
                                            <p class="text-xs text-slate-500 font-semibold mt-0.5">Jam Buka: {{ substr($schedule->jam_buka,0,5) }} – {{ substr($schedule->jam_tutup,0,5) }} WITA</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto shrink-0 border-t border-slate-100 pt-3 sm:border-0 sm:pt-0">
                                        <div class="bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 border border-green-200 rounded-xl">
                                            Kuota: {{ $sisaKuotaMenit }} mnt
                                        </div>
                                        <div class="text-xs text-slate-400 group-hover:text-green-600 font-bold tracking-wider transition-all uppercase flex items-center gap-1">
                                            Antrean
                                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-16 text-center text-slate-400 bg-white border border-slate-150 rounded-[20px] shadow-sm">
                                <p class="text-xs font-black uppercase tracking-widest">Jadwal belum tersedia</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL ANTREAN ALPINE -->
        <template x-teleport="body">
            <div x-cloak x-show="showModal" class="fixed inset-0 z-[9999]">
                <!-- Backdrop Blur -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                
                <!-- Modal Scroll Container -->
                <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-8 relative">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <div>
                                <h3 class="text-lg font-black text-blue-950">Daftar Antrean</h3>
                                <p class="text-sm font-bold text-red-600" x-text="activeDate"></p>
                            </div>
                            <button @click="showModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        <div class="p-6">
                            <template x-if="activeBookings.length === 0">
                                <div class="text-center py-10">
                                    <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <p class="text-lg font-bold text-slate-400">Belum ada antrean di tanggal ini.</p>
                                    <p class="text-sm text-slate-400 mt-2">Jadilah yang pertama booking!</p>
                                </div>
                            </template>

                            <template x-if="activeBookings.length > 0">
                                <div class="space-y-4">
                                    <template x-for="(b, idx) in activeBookings" :key="b.id">
                                        <div class="p-4 border border-slate-100 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-red-200 transition-colors bg-white">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center font-black text-lg border border-red-100 shadow-sm shrink-0">
                                                    <span x-text="idx + 1"></span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-blue-950 flex items-center gap-2">
                                                        <span x-text="b.kendaraan"></span>
                                                    </p>
                                                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider" x-text="b.plat_nomor_masked"></p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end gap-2 w-full sm:w-auto">
                                                <!-- Status Badge -->
                                                <template x-if="b.status === 'pending'">
                                                    <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-orange-100 whitespace-nowrap">Menunggu Konfirmasi</span>
                                                </template>
                                                <template x-if="b.status === 'approved'">
                                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-100 whitespace-nowrap">Antrean Disetujui</span>
                                                </template>
                                                <template x-if="b.status === 'in_progress'">
                                                    <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-yellow-100 whitespace-nowrap">Sedang Dikerjakan</span>
                                                </template>
                                                <template x-if="b.status === 'completed'">
                                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100 whitespace-nowrap">Selesai</span>
                                                </template>
                                                
                                                <!-- Paket Servis -->
                                                <p class="text-xs font-bold text-slate-400 max-w-[200px] truncate text-right" x-text="b.paket" :title="b.paket"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                            <p class="text-xs font-bold text-slate-400">*Plat nomor disensor sebagian untuk menjaga privasi.</p>
                            <a href="/login" class="px-6 py-2.5 bg-red-600 text-white font-bold rounded-full text-sm shadow-md hover:bg-red-700 transition-colors">Booking Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>
    
    <script id="public-bookings-data" type="application/json">
        @json($publicBookings)
    </script>
    <script>
        window.publicBookings = JSON.parse(document.getElementById('public-bookings-data').textContent);
    </script>

    <!-- Services Section -->
    <!-- Services Section -->
<div id="services" class="py-20 bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-red-600 font-bold uppercase tracking-wider text-sm">Fasilitas Bengkel</span>
            <h2 class="text-3xl font-extrabold text-blue-950 mt-2">Daftar Layanan Standar Dealer</h2>
            <p class="mt-3 text-blue-950 max-w-2xl mx-auto font-medium">Kami mengadopsi standar operasional Dealer Resmi. Sparepart yang digunakan adalah suku cadang orisinil dan mekanik bekerja menggunakan SOP industri.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @forelse($packages as $paket)
                @php
                    $isPopular = $paket->bookings_count > 0 && $paket->bookings_count >= $maxBookings;
                    
                    // Map image based on package name (Placeholder for now)
                    $image = 'https://images.unsplash.com/photo-1558981403-c5f91dbcf9ad?q=80&w=800&auto=format&fit=crop'; // Default
                    if (stripos($paket->nama_paket, 'oli') !== false) {
                        $image = 'https://images.unsplash.com/photo-1610647752706-3bb12232b3ab?q=80&w=800&auto=format&fit=crop';
                    } elseif (stripos($paket->nama_paket, 'lengkap') !== false || stripos($paket->nama_paket, 'berat') !== false) {
                        $image = 'https://images.unsplash.com/photo-1599812411566-b939591992ec?q=80&w=800&auto=format&fit=crop';
                    } elseif (stripos($paket->nama_paket, 'ringan') !== false || stripos($paket->nama_paket, 'reguler') !== false) {
                        $image = 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?q=80&w=800&auto=format&fit=crop';
                    }
                @endphp
                <div class="bg-white border {{ $isPopular ? 'border-red-600 ring-1 ring-red-600' : 'border-slate-200' }} rounded-xl overflow-hidden transition-all duration-300 group flex flex-col h-full">
                    
                    <!-- Image Area (Fixed Height) -->
                    <div class="relative h-44 overflow-hidden bg-slate-100 flex-shrink-0">
                        <img src="{{ $paket->image_path ? asset($paket->image_path) : 'https://images.unsplash.com/photo-1558981403-c5f91dbcf9ad?q=80&w=800&auto=format&fit=crop' }}" 
                             alt="{{ $paket->nama_paket }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/60 to-transparent"></div>
                        @if($isPopular)
                            <div class="absolute top-3 right-3 bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded shadow-sm uppercase tracking-wider">Terpopuler</div>
                        @endif
                        <div class="absolute bottom-3 left-4">
                            <h3 class="text-lg font-extrabold text-white leading-tight">{{ $paket->nama_paket }}</h3>
                        </div>
                    </div>

                    <!-- Content Area (Flexible, but will push footer down) -->
                    <div class="p-6 flex flex-col flex-1">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-3 flex-shrink-0">SOP Bengkel Resmi</p>
                        
                        <!-- Deskripsi dengan fixed height agar konsisten -->
                        <div class="text-slate-600 text-xs leading-relaxed font-medium mb-6 min-h-[80px]">
                            <p>{{ $paket->deskripsi ?? 'Pemeriksaan menyeluruh sesuai standar industri untuk memastikan performa motor tetap prima.' }}</p>
                        </div>
                        
                        <!-- Footer Section (akan selalu di bawah) -->
                        <div class="mt-auto pt-5 border-t border-slate-100 flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        {{ $paket->tipe === 'jasa_saja' ? 'Harga Paket' : 'Biaya Jasa' }}
                                    </span>
                                    @if($paket->harga_jasa == 0)
                                        <span class="text-base font-black text-emerald-600 uppercase">Gratis</span>
                                    @else
                                        <span class="text-lg font-black text-blue-950">Rp {{ number_format($paket->harga_jasa, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-col border-l border-slate-100 pl-4">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Material & Tambahan</span>
                                    @if($paket->tipe === 'jasa_saja')
                                        <span class="text-[10px] font-bold text-emerald-600 italic leading-tight">Sudah Termasuk</span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-600 italic leading-tight">Sesuai Kebutuhan</span>
                                    @endif
                                </div>
                            </div>
                            
                            <a href="/login" class="block w-full text-center py-2.5 bg-blue-950 text-white group-hover:bg-red-600 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold italic">Belum ada paket servis tersedia.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-10 text-center">
            <p class="text-sm text-blue-950 bg-red-50 inline-block px-6 py-3 rounded-2xl border border-red-100 font-medium max-w-4xl leading-relaxed">
                <span class="font-black text-red-600 uppercase tracking-wider text-xs block mb-1">Catatan Penting</span>
                Harga yang tertera di atas adalah <strong class="font-bold">estimasi biaya jasa dasar</strong>. Total biaya akhir (termasuk harga oli, jenis sparepart, dan tingkat kesulitan bongkar-pasang tiap jenis motor) akan dikonfirmasikan secara transparan oleh mekanik kami <strong class="font-bold">sebelum pengerjaan dimulai</strong>.
            </p>
        </div>
    </div>
</div>
<!-- Footer -->
        <footer class="bg-slate-900 pt-12 pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Logo & Description -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-8 w-auto" />
                            <span class="font-extrabold text-lg tracking-tight text-white">Skensa<span class="text-red-500">Moto</span></span>
                        </div>
                        <p class="text-slate-400 text-sm">Jurusan Teknik Sepeda Motor (TSM)<br>SMK Negeri 1 Denpasar</p>
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <h4 class="text-white font-bold mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            Lokasi
                        </h4>
                        <div class="flex items-start gap-2 text-slate-400 text-sm leading-relaxed mb-2">
                            <svg class="w-3 h-3 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Jl. H.O.S. Cokroaminoto No.84, Ubung, Denpasar Utara, Bali 80116</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-sm">
                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>(0361) 422401</span>
                        </div>
                    </div>
                    
                    <!-- Hours -->
                    <div>
                        <h4 class="text-white font-bold mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Jam Operasional
                        </h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between"><span class="text-slate-400">Senin - Kamis</span><span class="text-white font-medium">08:00 - 15:30</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Jumat</span><span class="text-white font-medium">08:00 - 11:30</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Sabtu - Minggu</span><span class="text-red-500 font-medium">Libur</span></div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom -->
                <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium">
                    <p class="text-slate-500">&copy; 2026 Jurusan TSM - SMK Negeri 1 Denpasar. Dibuat oleh Siswa untuk Masyarakat.</p>
                    <span class="text-red-500 font-bold">Standard Operating Procedure (SOP) Tersertifikasi</span>
                </div>
            </div>
        </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar');
            const navContainer = document.getElementById('nav-container');
            
            const handleScroll = () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('is-scrolled');
                    navContainer.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                    navContainer.classList.remove('is-scrolled');
                }
            };

            window.addEventListener('scroll', handleScroll);
            // Run once on load
            handleScroll();
        });
    </script>
</body>
</html>


