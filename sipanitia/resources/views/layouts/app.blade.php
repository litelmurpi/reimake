<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiPanitia') }}</title>

        <!-- Fonts & Icons -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com" rel="preconnect" />
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
            .pt-safe { padding-top: env(safe-area-inset-top); }
            body { min-height: max(100vh, 100dvh); }
            /* Hide scrollbar for clean look */
            ::-webkit-scrollbar { display: none; }
        </style>
    </head>
    <body class="bg-surface-container-lowest font-body-md text-on-surface antialiased min-h-screen flex flex-col pt-safe">
        
        <!-- TopAppBar (Premium Glassmorphism) -->
        <header class="bg-primary/95 backdrop-blur-2xl border-b border-white/10 fixed top-0 w-full z-50 shadow-[0_4px_30px_rgba(0,0,0,0.1)]">
            <div class="flex items-center justify-between px-container-padding h-top-bar-height transition-all duration-700 ease-spring">
                <div class="font-headline-lg font-bold text-on-primary tracking-tight">
                    {{ config('app.name', 'SiPanitia') }}
                </div>
                <div class="flex items-center space-x-4">
                    <button aria-label="Notifications" class="relative group hover:scale-105 active:scale-95 transition-all duration-300 ease-spring text-on-primary">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                        <!-- Notification dot -->
                        <span class="absolute top-0 right-0 bg-error w-2.5 h-2.5 rounded-full border-2 border-primary"></span>
                    </button>
                    <!-- User Avatar -->
                    <div class="w-8 h-8 rounded-full overflow-hidden border border-white/20 shadow-glass cursor-pointer group hover:scale-105 active:scale-95 transition-all duration-300 ease-spring">
                        <img alt="User" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" />
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-grow pt-[calc(56px+24px)] pb-bottom-nav-height bg-surface-container-lowest transition-all duration-700 ease-spring">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation Bar (Glassmorphic Doppelrand) -->
        <nav class="bg-surface/90 backdrop-blur-xl fixed bottom-0 w-full z-50 pb-safe border-t border-outline-variant/30 shadow-[0_-8px_30px_rgba(0,0,0,0.04)]">
            <div class="flex justify-around items-center h-bottom-nav-height px-2">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-secondary' }} group hover:bg-primary/5 rounded-2xl active:scale-95 transition-all duration-500 ease-spring w-16 h-14">
                    <span class="material-symbols-outlined group-hover:-translate-y-0.5 transition-transform duration-500 ease-spring" style="{{ request()->routeIs('dashboard') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">dashboard</span>
                    <span class="font-label-sm mt-1">Dasbor</span>
                </a>

                <!-- Tugas -->
                <a href="#" class="flex flex-col items-center justify-center text-secondary group hover:bg-primary/5 rounded-2xl active:scale-95 transition-all duration-500 ease-spring w-16 h-14">
                    <span class="material-symbols-outlined group-hover:-translate-y-0.5 transition-transform duration-500 ease-spring">assignment</span>
                    <span class="font-label-sm mt-1">Tugas</span>
                </a>

                <!-- Logistik -->
                <a href="#" class="flex flex-col items-center justify-center text-secondary group hover:bg-primary/5 rounded-2xl active:scale-95 transition-all duration-500 ease-spring w-16 h-14">
                    <span class="material-symbols-outlined group-hover:-translate-y-0.5 transition-transform duration-500 ease-spring">inventory_2</span>
                    <span class="font-label-sm mt-1">Logistik</span>
                </a>

                <!-- Profil -->
                <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('profile') ? 'text-primary' : 'text-secondary' }} group hover:bg-primary/5 rounded-2xl active:scale-95 transition-all duration-500 ease-spring w-16 h-14">
                    <span class="material-symbols-outlined group-hover:-translate-y-0.5 transition-transform duration-500 ease-spring" style="{{ request()->routeIs('profile') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">person</span>
                    <span class="font-label-sm mt-1">Profil</span>
                </a>

            </div>
            
            <!-- Home Indicator (iOS style) -->
            <div class="w-full flex justify-center pb-2 pt-1">
                <div class="w-1/3 h-1 bg-on-surface-variant/20 rounded-full"></div>
            </div>
        </nav>
    </body>
</html>
