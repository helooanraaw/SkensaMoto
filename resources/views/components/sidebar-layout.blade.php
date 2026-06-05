<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - MotoSkensa</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/MotoSkensaLogo1.png') }}" type="image/png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Urbanist', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

</head>
<body class="bg-slate-50 text-blue-950 antialiased selection:bg-red-100 selection:text-red-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-md lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transition-transform duration-300 lg:translate-x-0 flex flex-col shadow-sm">
        
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center px-8 border-b border-slate-100 shrink-0">
            @php $appSetting = \App\Models\Setting::first(); @endphp
            <a href="/" class="flex items-center gap-2">
                @if($appSetting && $appSetting->logo_path)
                    <img src="{{ asset($appSetting->logo_path) }}" alt="Logo" class="h-8 w-auto">
                @else
                    <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="Logo" class="h-8 w-auto">
                @endif
                @if($appSetting && $appSetting->nama_bengkel)
                    <span class="font-extrabold text-xl tracking-tight text-blue-950">{{ $appSetting->nama_bengkel }}</span>
                @else
                    <span class="font-extrabold text-xl tracking-tight"><span class="text-red-600">Moto</span><span class="text-blue-950">Skensa</span></span>
                @endif
            </a>
        </div>

        <!-- Sidebar Content -->
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            
            @php
                $userRole = auth()->user()->role;
                $antreanBadge = 0;

                if (in_array($userRole, ['superadmin', 'admin'])) {
                    $antreanBadge = \App\Models\Booking::where('status', 'pending')->count();
                } elseif ($userRole === 'mekanik') {
                    $antreanBadge = \App\Models\Booking::where('status', 'approved')
                        ->orWhere(function($query) {
                            $query->where('status', 'in_progress')->where('quotation_status', 'approved');
                        })->count();
                }
            @endphp

            @if(in_array($userRole, ['superadmin', 'admin', 'mekanik']))
            <!-- Workshop Staff Menus -->
            @if($userRole === 'superadmin')
            <!-- Superadmin (Kepsek / Wakasek) Menu Layout -->
            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Laporan & Sistem</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.recap.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.recap.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    Rekap Keuangan
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Manajemen Akun
                </a>

            </div>

            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Monitoring Bengkel (Lihat Saja)</p>
                <a href="{{ route('admin.bookings.index') }}" class="flex justify-between items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.bookings.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Antrean Servis
                    </div>
                    @if($antreanBadge > 0)
                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $antreanBadge }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.schedules.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal Harian
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.inventory.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Stok Barang
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.services.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Paket Servis
                </a>
            </div>
            @else
            <!-- Original Admin / Mekanik Menu Layout -->
            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Operasional</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex justify-between items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.bookings.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Antrean Servis
                    </div>
                    @if($antreanBadge > 0)
                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $antreanBadge }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.schedules.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal Harian
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Logistik & Jasa</p>
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.inventory.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Stok Barang
                </a>
                @if(in_array($userRole, ['superadmin', 'admin']))
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.services.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Paket Servis
                </a>
                @endif
            </div>
            @endif
            @elseif(auth()->user()->role === 'user')
            <!-- User Menus -->
            <div class="space-y-1">
                <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Menu Pelanggan</p>
                <a href="{{ route('user.dashboard') }}" class="flex justify-between items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('user.dashboard') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard Utama
                    </div>

                </a>
                <a href="{{ route('user.calendar.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('user.calendar.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kalender Booking
                </a>
                <a href="{{ route('user.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('user.history') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Servis
                </a>
                <a href="{{ route('user.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('user.settings.*') ? 'bg-red-50 text-red-600' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-950' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pengaturan Profil
                </a>
            </div>
            @endif

        </div>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-slate-100 shrink-0">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50">
                <div class="w-10 h-10 rounded-full bg-blue-950 text-white flex items-center justify-center font-bold text-sm shrink-0">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-blue-950 truncate">{{ auth()->user()->name }}</p>
                    <!-- <p class="text-xs text-slate-500 truncate">{{ ucfirst(auth()->user()->role) }}</p> -->
                </div>
            </div>
            <div class="mt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- Topbar (Mobile Only) -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 lg:hidden shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="Logo" class="h-8 w-auto">
            </div>
            <button @click="sidebarOpen = true" class="p-2 text-slate-500 hover:bg-slate-100 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </header>

        <!-- Page Header -->
        <div class="pt-8 px-6 sm:px-10 w-full flex justify-between items-end">
            <h1 class="text-3xl font-black tracking-tight text-blue-950">{{ $title ?? 'Dashboard' }}</h1>
            
            <!-- Notification Bell -->
            <div class="relative group cursor-pointer">
                <div class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-950 hover:border-blue-950 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                @php
                    $totalNotif = $antreanBadge;
                @endphp
                @if($totalNotif > 0)
                    <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-slate-50"></div>
                @endif
                
                <!-- Dropdown -->
                <div class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50">
                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Notifikasi Baru ({{ $totalNotif }})</p>
                    </div>
                    <div class="p-2 max-h-64 overflow-y-auto">
                        @if($totalNotif == 0)
                            <div class="p-4 text-center text-sm text-slate-400">Belum ada notifikasi.</div>
                        @else

                            @if(in_array(auth()->user()->role, ['superadmin', 'admin']) && $antreanBadge > 0)
                                <a href="{{ route('admin.bookings.index') }}" class="block p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                    <p class="text-sm font-bold text-blue-950">Ada Booking Baru Masuk!</p>
                                    <p class="text-xs text-slate-500 mt-1">Terdapat {{ $antreanBadge }} antrean servis yang menunggu persetujuan (Approve) Anda.</p>
                                </a>
                            @endif
                            @if(auth()->user()->role === 'mekanik' && $antreanBadge > 0)
                                <a href="{{ route('admin.bookings.index') }}" class="block p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                    <p class="text-sm font-bold text-blue-950">Motor Siap Dikerjakan!</p>
                                    <p class="text-xs text-slate-500 mt-1">Ada {{ $antreanBadge }} antrean motor yang menunggu tindakan Anda.</p>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
            @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mt-4 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="mt-4 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm font-bold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="flex-1 p-6 sm:p-10 w-full">
            {{ $slot }}
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{ $scripts ?? '' }}

    <!-- Global Custom Confirm Modal -->
    <template x-teleport="body">
        <div x-data="{ 
                open: false, 
                message: '', 
                onConfirm: null,
                init() {
                    window.showCustomConfirm = (msg, callback) => {
                        this.message = msg;
                        this.onConfirm = callback;
                        this.open = true;
                    }
                }
            }"
            x-cloak
            x-show="open" 
            class="fixed inset-0 z-[99999]"
        >
            <!-- Backdrop Blur -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"
            ></div>

            <!-- Modal Content -->
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="bg-white rounded-[28px] shadow-2xl w-full max-w-sm overflow-hidden p-6 relative border border-slate-100"
                >
                    <div class="text-center space-y-4">
                        <div>
                            <h3 class="text-lg font-black text-blue-950">Konfirmasi Tindakan</h3>
                            <p class="text-sm text-slate-500 font-medium mt-2 leading-relaxed" x-text="message"></p>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="open = false" class="flex-1 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 font-bold rounded-full text-sm transition-colors">
                                Batal
                            </button>
                            <button type="button" @click="open = false; if(onConfirm) onConfirm();" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full text-sm transition-colors shadow-lg shadow-red-600/25">
                                Ya, Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        // Listen in capturing phase to intercept before inline onsubmit runs
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const onsubmitAttr = form.getAttribute('onsubmit');
            if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                if (!form.dataset.confirmed) {
                    e.preventDefault();
                    e.stopPropagation(); // Stop target phase propagation to prevent inline onsubmit confirm() from executing
                    
                    // Match single or double quotes
                    const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                    const message = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    
                    if (window.showCustomConfirm) {
                        window.showCustomConfirm(message, function() {
                            form.dataset.confirmed = 'true';
                            form.submit(); // Submit programmatically
                        });
                    } else {
                        // Fallback to browser confirm if Alpine isn't ready
                        if (confirm(message)) {
                            form.dataset.confirmed = 'true';
                            form.submit();
                        }
                    }
                }
            }
        }, true); // Capturing phase is KEY here
    </script>
</body>
</html>
