<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @yield('html_attributes', 'class="dark"')>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Favicon -->
    @php
        $profil = \App\Models\Profil::first();
        $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @yield('favicon_extra')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @yield('fonts')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Preline JS -->
    <script src="https://cdn.jsdelivr.net/npm/preline@2.0.4/dist/preline.min.js"></script>
    
    @yield('scripts_head')

    <!-- Styles -->
    @livewireStyles
    @yield('styles')
</head>

<body class="@yield('body_class', 'font-sans antialiased')">
    {{-- Hapus div container_class agar tidak menyebabkan kotak hitam --}}
    @hasSection('custom_header')
        @yield('custom_header')
    @elseif(isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="@yield('main_class')">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @hasSection('custom_footer')
        @yield('custom_footer')
    @endif
    </div>

    @stack('modals')
    @yield('before_scripts')
    @livewireScripts
    @yield('after_scripts')
</body>

</html>
