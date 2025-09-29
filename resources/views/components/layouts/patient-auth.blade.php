<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Antrian Live') }} - {{ $title ?? 'Pasien' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo -->
        <div class="w-full sm:max-w-md px-6 py-4">
            <a href="/" class="flex justify-center">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <!-- Card Form -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

        <!-- Footer Links -->
        <div class="w-full sm:max-w-md px-6 py-4 text-center text-sm text-gray-600">
            @if (Route::has('patient.login'))
                <a href="{{ route('patient.login') }}" class="text-blue-600 hover:text-blue-500">
                    {{ __('Sudah punya akun? Masuk disini') }}
                </a>
            @endif

            @if (Route::has('patient.register'))
                <span class="mx-2">•</span>
                <a href="{{ route('patient.register') }}" class="text-blue-600 hover:text-blue-500">
                    {{ __('Daftar Akun Baru') }}
                </a>
            @endif
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>