<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Antrian' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('patient.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-2 sm:ml-10 sm:flex">
                            @auth('patient')
                                <a href="{{ route('patient.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('patient.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    {{ __('Dashboard') }}
                                </a>
                                <a href="{{ route('patient.ticket') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('patient.ticket') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    {{ __('Ambil Tiket') }}
                                </a>
                            @endauth
                            
                            @guest('patient')
                                <a href="{{ route('patient.login') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('patient.login') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    {{ __('Login') }}
                                </a>
                                <a href="{{ route('patient.register') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('patient.register') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    {{ __('Register') }}
                                </a>
                            @endguest
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    @auth('patient')
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                            <button id="hs-dropdown-with-header" type="button" class="hs-dropdown-toggle inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-slate-900 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800 px-4 py-2">
                                {{ Auth::guard('patient')->user()->name }}
                                <svg class="hs-dropdown-open:rotate-180 w-2.5 h-2.5 text-gray-600" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[15rem] bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                                <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Akun</p>
                                </div>
                                <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        Profil Saya
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-md text-sm text-red-600 hover:bg-red-50 focus:ring-2 focus:ring-red-500 dark:text-red-400 dark:hover:bg-gray-700" href="{{ route('patient.logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                            <polyline points="16 17 21 12 16 7"/>
                                            <line x1="21" x2="9" y1="12" y2="12"/>
                                        </svg>
                                        Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('patient.logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    @auth('patient')
                        <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('patient.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('patient.ticket') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('patient.ticket') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ __('Ambil Tiket') }}
                        </a>
                        
                        <!-- Mobile Profile Links -->
                        <div class="border-t border-gray-200 pt-4 pb-3">
                            <div class="px-4">
                                <div class="text-base font-medium text-gray-800">{{ Auth::guard('patient')->user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ Auth::guard('patient')->user()->email }}</div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <form method="POST" action="{{ route('patient.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:bg-red-50 hover:text-red-800">
                                        {{ __('Keluar') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('patient.login') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('patient.login') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('patient.register') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('patient.register') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ __('Register') }}
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if(isset($slot) && trim($slot) !== '')
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
    </div>

    @stack('modals')

    @livewireScripts
    
    <!-- Preline UI Initialization Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            window.HSStaticMethods.autoInit();
        });
    </script>
</body>
</html>
