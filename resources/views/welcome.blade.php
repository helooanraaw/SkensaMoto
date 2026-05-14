<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkensaMotoHub - TEFA TSM SMK Negeri 1 Denpasar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Menggunakan Font Urbanist -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Urbanist', sans-serif; }
        .honda-red { color: #E3000F; }
        .bg-honda-red { background-color: #E3000F; }
        .border-honda-red { border-color: #E3000F; }
        .hover-bg-honda-red-dark:hover { background-color: #cc000e; }
    </style>
</head>
<body class="bg-white text-blue-950 antialiased selection:bg-red-100 selection:text-red-900">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full top-0 z-50 pt-4 pb-2 transition-all duration-500 ease-out group/nav transform origin-top border border-transparent [&.is-scrolled]:top-4 [&.is-scrolled]:w-[95%] [&.is-scrolled]:lg:w-[75%] [&.is-scrolled]:left-1/2 [&.is-scrolled]:-translate-x-1/2 [&.is-scrolled]:bg-white/85 [&.is-scrolled]:backdrop-blur-md [&.is-scrolled]:shadow-2xl [&.is-scrolled]:rounded-full [&.is-scrolled]:border-white/50 [&.is-scrolled]:pt-0 [&.is-scrolled]:pb-0 [&.is-scrolled]:scale-95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 px-2 lg:px-4">
                <!-- Logo (Kiri) -->
                <div class="flex items-center w-1/4">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-10 w-auto drop-shadow-md group-[.is-scrolled]/nav:drop-shadow-none transition-all" />
                        <div class="flex flex-col">
                            <span class="font-extrabold text-2xl leading-tight tracking-tight text-blue-950 drop-shadow-md group-[.is-scrolled]/nav:drop-shadow-none transition-all">Skensa<span class="text-red-600">Moto</span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Menu (Tengah) -->
                <!-- Posisi dikembalikan ke tengah, warna disesuaikan 1 per 1 secara manual -->
                <div class="hidden lg:flex items-center justify-center w-2/4">
                    <div class="flex items-center gap-8 px-2 transition-all">
                        <a href="#" class="text-blue-950 hover:text-red-600 font-bold transition-all group-[.is-scrolled]/nav:text-blue-950 group-[.is-scrolled]/nav:hover:text-red-600">Beranda</a>
                        <a href="#services" class="text-blue-950 hover:text-red-600 font-bold transition-all group-[.is-scrolled]/nav:text-blue-950 group-[.is-scrolled]/nav:hover:text-red-600">Layanan Standar</a>
                        <a href="#schedule" class="text-white/90 hover:text-white font-bold transition-all drop-shadow-md group-[.is-scrolled]/nav:text-blue-950 group-[.is-scrolled]/nav:drop-shadow-none group-[.is-scrolled]/nav:hover:text-red-600">Jadwal Booking</a>
                    </div>
                </div>

                <!-- Tombol (Kanan) -->
                <div class="hidden lg:flex items-center justify-end w-1/4">
                    <a href="/login" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-xl transition-all">Booking Antrian</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <!-- Full Bleed Image Background (Right 60%) -->
        <div class="absolute top-0 right-0 w-full lg:w-[55%] h-full z-0">
            <!-- Background Image -->
            <img src="https://s0.bukalapak.com/bukalapak-kontenz-production/content_attachments/88030/original/bengkel_ahass_terdekat_main.jpg" alt="Motorcycle Workshop" class="absolute inset-0 w-full h-full object-cover object-center" />
            
            <!-- Dark Overlay (Sedikit lebih gelap) -->
            <div class="absolute inset-0 bg-slate-950/10"></div>
            
            <!-- Pelindung Teks Navbar (Agar menu putih di kanan tetap terbaca walau gambar terang) -->
            <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-slate-950/50 to-transparent"></div>
            <!-- Gradient Fade-in dari Kiri (Membuat efek memudar menyentuh area tengah/50% layar) -->
            <div class="absolute inset-y-0 left-0 w-1/4 bg-gradient-to-r from-white to-transparent"></div>
            
            <!-- Gradient Fade-in dari Bawah khusus mobile -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-white to-transparent lg:hidden"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="relative pb-16 pt-32 sm:pb-24 sm:pt-40 lg:pb-32 lg:pt-48 px-4 sm:px-6 lg:px-8 flex flex-col items-start min-h-[700px] justify-center">
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
                        <a href="/login" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-xl font-bold text-lg shadow-2xl transition-all flex items-center justify-center gap-2 w-full sm:w-auto ">
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
    <div id="schedule" class="py-16 bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-10 pb-6 border-b border-gray-200">
                <h2 class="text-3xl font-extrabold text-blue-950">Jadwal Antrian Bengkel</h2>
                <p class="mt-2 text-blue-950 font-medium max-w-2xl">
                    Untuk menjaga kualitas servis, kami membatasi jumlah motor setiap harinya.
                    Silakan pilih hari yang tersedia (Warna Hijau) untuk melakukan booking tanpa antri lama di lokasi.
                </p>
            </div>

            <!-- Booking Steps -->
            <div class="flex items-center justify-between mb-10 bg-white border border-gray-200 rounded-lg px-10 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 font-bold flex items-center justify-center">1</div>
                    <span class="font-semibold text-blue-950">Pilih Tanggal</span>
                </div>
                <div class="flex items-center gap-3 opacity-40">
                    <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 font-bold flex items-center justify-center">2</div>
                    <span class="font-semibold text-blue-950">Isi Keluhan Motor</span>
                </div>
                <div class="flex items-center gap-3 opacity-40">
                    <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 font-bold flex items-center justify-center">3</div>
                    <span class="font-semibold text-blue-950">Bawa Motor Sesuai Jadwal</span>
                </div>
            </div>

            <!-- 5 Jadwal — BERJEJER, TIDAK SCROLL -->
            <div class="grid grid-cols-5 gap-5">
                @forelse($schedules as $key => $schedule)
                    @php
                        $dateObj = \Carbon\Carbon::parse($schedule->tanggal)->locale('id');
                        $isToday   = $dateObj->isToday();
                        $sisaKuotaMenit = $schedule->kapasitas_menit - $schedule->terpakai_menit;
                        $sisaKuotaMotor = max(0, floor($sisaKuotaMenit / 60)); // Asumsi 1 motor = 60 menit
                        $isFull    = $sisaKuotaMotor <= 0;
                        $isHoliday = $schedule->kapasitas_menit == 0;
                        $isEmpty   = false;
                    @endphp

                    @if($isEmpty)
                        {{-- Slot belum diisi admin --}}
                        <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-7 flex flex-col items-center justify-between opacity-50 cursor-not-allowed" style="min-height:290px">
                            <div class="text-center">
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">{{ $dateObj->translatedFormat('l') }}</p>
                                <p class="text-5xl font-black text-gray-300 leading-none mb-5">{{ $dateObj->format('j M') }}</p>
                            </div>
                            <div class="w-full">
                                <div class="py-3 text-center text-xs font-bold text-gray-400 border border-dashed border-gray-300 rounded-xl">Belum Dijadwalkan</div>
                            </div>
                        </div>

                    @elseif($isHoliday)
                        <div class="bg-white border border-gray-200 rounded-2xl p-7 flex flex-col items-center justify-between" style="min-height:290px">
                            <div class="text-center">
                                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-5">{{ $dateObj->translatedFormat('l') }}</p>
                                <p class="text-5xl font-black text-blue-950 leading-none mb-5">{{ $dateObj->format('j M') }}</p>
                                <span class="inline-block bg-slate-100 text-slate-500 text-xs font-bold px-4 py-1.5 rounded-full">Libur</span>
                            </div>
                            <div class="w-full">
                                <div class="py-3 text-center text-sm font-bold text-slate-400 border border-slate-200 rounded-xl">Libur Sekolah</div>
                            </div>
                        </div>

                    @elseif($isFull)
                        <div class="bg-white border {{ $isToday ? 'border-red-400' : 'border-gray-200' }} rounded-2xl p-7 flex flex-col items-center justify-between" style="min-height:290px">
                            <div class="text-center">
                                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-5">{{ $isToday ? 'HARI INI' : $dateObj->translatedFormat('l') }}</p>
                                <p class="text-5xl font-black text-blue-950 leading-none mb-5">{{ $dateObj->format('j M') }}</p>
                                <span class="inline-block bg-slate-100 text-slate-500 text-xs font-bold px-4 py-1.5 rounded-full">{{ substr($schedule->jam_buka,0,5) }} – {{ substr($schedule->jam_tutup,0,5) }} WITA</span>
                            </div>
                            <div class="w-full">
                                <div class="py-3 text-center text-sm font-bold text-red-500 bg-red-50 border border-red-200 rounded-xl">Kuota Penuh</div>
                            </div>
                        </div>

                    @else
                        <a href="/login" class="block bg-white border {{ $isToday ? 'border-red-400' : 'border-gray-200 hover:border-green-400' }} rounded-2xl p-7 flex flex-col items-center justify-between transition-all duration-200 group" style="min-height:290px">
                            <div class="text-center">
                                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-5">{{ $isToday ? 'HARI INI' : $dateObj->translatedFormat('l') }}</p>
                                <p class="text-5xl font-black text-blue-950 leading-none mb-5 group-hover:text-green-600 transition-colors">{{ $dateObj->format('j M') }}</p>
                                <span class="inline-block bg-slate-100 text-slate-500 text-xs font-bold px-4 py-1.5 rounded-full">{{ substr($schedule->jam_buka,0,5) }} – {{ substr($schedule->jam_tutup,0,5) }} WITA</span>
                            </div>
                            <div class="w-full">
                                <div class="py-3 text-center text-sm font-bold text-green-600 bg-green-50 border border-green-200 rounded-xl group-hover:bg-green-100 transition-colors">Sisa {{ $sisaKuotaMotor }} Kuota</div>
                            </div>
                        </a>
                    @endif

                @empty
                    <div class="col-span-5 py-12 text-center text-slate-400">
                        <p class="text-lg font-bold">Jadwal minggu ini belum tersedia.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="py-20 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-red-600 font-bold uppercase tracking-wider text-sm">Fasilitas Bengkel</span>
                <h2 class="text-3xl font-extrabold text-blue-950 mt-2">Daftar Layanan Standar Dealer</h2>
                <p class="mt-3 text-blue-950 max-w-2xl mx-auto font-medium">Kami mengadopsi standar operasional Dealer Resmi. Sparepart yang digunakan adalah suku cadang orisinil dan mekanik bekerja menggunakan SOP industri.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-8 hover:border-red-600 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-red-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-950 mb-2">Servis Berkala (Tune Up)</h3>
                    <p class="text-blue-950 mb-6 text-sm leading-relaxed font-medium">Paket pemeriksaan rutin mulai dari pembersihan karburator/injeksi, cek busi, setel klep, hingga cek kelistrikan layaknya SOP AHASS.</p>
                    <div class="text-sm font-bold text-red-600 border-t border-gray-100 pt-4">Estimasi Biaya Jasa: Rp 50.000</div>
                </div>

                <!-- Service 2 -->
                <div class="bg-white border-2 border-red-600 rounded-xl p-8 relative shadow-lg">
                    <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">Paling Sering Dipesan</div>
                    <div class="w-12 h-12 bg-red-600 text-white rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-950 mb-2">Ganti Oli Mesin & Gardan</h3>
                    <p class="text-blue-950 mb-6 text-sm leading-relaxed font-medium">Penggantian oli dengan menggunakan pelumas asli pabrikan. Penting untuk menjaga performa mesin agar tetap halus dan awet.</p>
                    <div class="text-sm font-bold text-red-600 border-t border-gray-100 pt-4">Harga Tergantung Jenis Oli</div>
                </div>

                <!-- Service 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-8 hover:border-red-600 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-red-600 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-950 mb-2">Servis Area CVT (Matic)</h3>
                    <p class="text-blue-950 mb-6 text-sm leading-relaxed font-medium">Solusi motor matic yang tarikannya berat atau bergetar (gredek). Kami bersihkan dan beri pelumas ulang (grease) pada area transmisi.</p>
                    <div class="text-sm font-bold text-red-600 border-t border-gray-100 pt-4">Estimasi Biaya Jasa: Rp 40.000</div>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <p class="text-sm text-blue-950 bg-gray-50 inline-block px-4 py-2 rounded-full border border-gray-200 font-medium">
                    <span class="font-bold text-blue-950">Penting:</span> Biaya di atas hanya estimasi jasa. Jika ada sparepart yang perlu diganti, admin/mekanik akan konfirmasi ke Anda terlebih dahulu.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t-4 border-red-600 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-8 w-auto" />
                        <span class="font-extrabold text-xl tracking-tight text-red-600">Skensa<span class="text-blue-950">Moto</span></span>
                    </div>
                    <p class="text-blue-950 text-sm leading-relaxed font-bold mb-2">Bengkel Motor Teknologi Sekolah</p>
                    <p class="text-blue-950 text-sm leading-relaxed font-medium">Jurusan Teknik Sepeda Motor (TSM)<br>SMK Negeri 1 Denpasar</p>
                </div>
                <div>
                    <h4 class="text-blue-950 font-bold mb-4 uppercase text-sm tracking-wider">Lokasi Bengkel</h4>
                    <ul class="text-blue-950 space-y-3 text-sm font-medium">
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jl. H.O.S. Cokroaminoto No.84, Ubung, Kec. Denpasar Utara, Kota Denpasar, Bali 80116</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>(0361) 422401</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-blue-950 font-bold mb-4 uppercase text-sm tracking-wider">Jam Praktik & Pelayanan</h4>
                    <ul class="text-blue-950 text-sm space-y-2 font-medium">
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Senin - Kamis</span> <span class="font-bold">08:00 - 15:30 WITA</span></li>
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Jumat</span> <span class="font-bold">08:00 - 11:30 WITA</span></li>
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Sabtu</span> <span>Libur Praktik</span></li>
                        <li class="flex justify-between text-red-600 font-bold pt-1"><span>Minggu</span> <span>Libur Sekolah</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium">
                <p class="text-blue-950">&copy; 2026 Jurusan TSM - SMK Negeri 1 Denpasar. Dibuat oleh Siswa untuk Masyarakat.</p>
                <span class="text-red-600 font-bold">Standard Operating Procedure (SOP) Tersertifikasi</span>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                }
            });
        });
    </script>
</body>
</html>


