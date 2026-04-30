<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-blue-950 antialiased selection:bg-red-100 selection:text-red-900" style="font-family: 'Urbanist', sans-serif;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
            <div class="w-full sm:max-w-lg mt-6 px-12 pt-12 pb-14 bg-white shadow-2xl sm:rounded-[2rem] border border-slate-100 relative">
                
                <!-- Logo di dalam container -->
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center gap-2 group hover:scale-105 transition-transform">
                        <img src="{{ asset('images/MotoSkensaLogo1.png') }}" alt="MotoSkensa Logo" class="h-12 w-auto drop-shadow-sm" />
                        <span class="font-extrabold text-3xl leading-tight tracking-tight text-blue-950 drop-shadow-sm">Skensa<span class="text-red-600">Moto</span></span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
