<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MotoSkensa' }} — MotoSkensa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Urbanist', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-white/10 hover:text-white transition-all duration-150; }
        .sidebar-link.active { @apply bg-white/15 text-white; }
    </style>
</head>
<body class="bg-slate-100 antialiased" style="font-family:'Urbanist',sans-serif;">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 bg-gradient-to-b from-blue-950 to-blue-900 shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-red-600 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-black text-base leading-none">MotoSkensa</p>
                <p class="text-blue-300 text-xs font-medium">
                    @auth
                        {{ Auth::user()->role === 'admin' ? 'Admin Panel' : 'Dashboard Saya' }}
                    @endauth
                </p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
            @auth
                @if(Auth::user()->role === 'admin')
                    {{-- ADMIN MENU --}}
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-2 mb-2">Utama</p>

                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-2 mt-4 mb-2">Manajemen</p>

                    <a href="{{ route('admin.dashboard') }}#jadwal"
                        class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Jadwal Bengkel
                    </a>

                    <a href="{{ route('admin.dashboard') }}#booking"
                        class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Booking Masuk
                        @php $pending = \App\Models\Booking::where('status','menunggu')->count(); @endphp
                        @if($pending > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pending }}</span>
                        @endif
                    </a>

                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-2 mt-4 mb-2">Akun</p>

                @else
                    {{-- USER MENU --}}
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-2 mb-2">Menu</p>

                    <a href="{{ route('dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('dashboard') }}#booking"
                        class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        Booking Saya
                        @php
                            $unread = \App\Models\Message::where('is_admin', true)
                                ->where('is_read', false)
                                ->whereHas('booking', fn($q) => $q->where('user_id', Auth::id()))
                                ->count();
                        @endphp
                        @if($unread > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unread }}</span>
                        @endif
                    </a>

                    <a href="/"
                        class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5v-6h4v6h5a1 1 0 001-1V10"/>
                        </svg>
                        Kembali ke Beranda
                    </a>

                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-2 mt-4 mb-2">Akun</p>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
            @endauth
        </nav>

        {{-- User Info + Logout --}}
        @auth
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-red-600 flex items-center justify-center text-white font-black text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-blue-300 text-xs truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2 text-red-400 hover:text-red-300 hover:bg-white/10 font-semibold text-xs px-3 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
        @endauth
    </aside>

    {{-- ===== OVERLAY (mobile) ===== --}}
    <div id="overlay" class="fixed inset-0 z-30 bg-black/50 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-100 px-6 py-4 flex items-center gap-4 shrink-0 shadow-sm">
            {{-- Hamburger (mobile) --}}
            <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-lg font-extrabold text-blue-950 leading-none">{{ $title ?? 'Dashboard' }}</h1>
                @isset($subtitle)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
                @endisset
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden sm:block">
                    {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('l, d F Y') }}
                </span>
                <div class="w-8 h-8 rounded-xl bg-blue-950 flex items-center justify-center text-white font-black text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div id="flash-ok" class="mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl text-sm font-semibold">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 px-5 py-3 rounded-2xl text-sm font-semibold">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Scrollable Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const ov = document.getElementById('overlay');
        sb.classList.toggle('-translate-x-full');
        ov.classList.toggle('hidden');
    }
    const flash = document.getElementById('flash-ok');
    if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>
</body>
</html>
