<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SkensaMotoHub') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/MotoSkensaLogo1.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Urbanist', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-blue-950 antialiased bg-slate-50 selection:bg-red-100 selection:text-red-900">
        <div class="min-h-screen flex flex-col justify-center items-center p-6">
            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl shadow-slate-200/50 overflow-hidden rounded-[24px] border-2 border-slate-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
