<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Dark mode detection script (runs before page render) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ config('app.name', 'Runa Maki') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Fallback to ensure banner hides on small screens if Tailwind isn't applied immediately */
        @media (max-width: 767px) {
            .desktop-banner { display: none !important; }
            .mobile-banner-icon { display: block !important; }
        }
        @media (min-width: 768px) {
            .mobile-banner-icon { display: none !important; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="min-h-screen flex">
        @auth
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 transition-all duration-300 shadow-sm">
            <!-- Logo & Brand -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight text-gray-800 dark:text-white">
                    <span class="text-indigo-600 dark:text-indigo-400">Runa</span> <span class="text-gray-600 dark:text-gray-300">Maki</span>
                </a>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-semibold ring-1 ring-gray-100 dark:ring-gray-600 shadow-sm">
                        {{ Auth::user()->iniciales }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">{{ Auth::user()->nivel }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">• {{ Auth::user()->nivel_emoji }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                    <span>Runas</span>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->puntos_runa }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('habilidades.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('habilidades.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Habilidades
                </a>
                <a href="{{ route('trueques.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('trueques.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Mis Trueques
                </a>
                <a href="{{ route('perfil.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('perfil.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Mi Perfil
                </a>
            </nav>

            <!-- Actions -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 space-y-2">
                <!-- Dark Mode Toggle -->
                <button id="dark-mode-toggle" class="flex items-center justify-center gap-2 w-full px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg id="dark-mode-icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="dark-mode-icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span id="dark-mode-text">Modo Oscuro</span>
                </button>
                
                <a href="{{ route('habilidades.create') }}" class="block w-full px-4 py-2 border border-indigo-200 dark:border-indigo-700 text-indigo-700 dark:text-indigo-400 text-sm font-medium rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition text-center">
                    + Nueva Habilidad
                </a>
                @if(Auth::user()->isAdmin())
                    <span class="block text-center text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 py-1 rounded">Admin</span>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-gray-700 dark:text-gray-300 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition text-center">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay (visible when sidebar is open on mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity lg:hidden"></div>
        @endauth

        <!-- Main Content Area -->
        <div class="flex-1 {{ auth()->check() ? 'lg:ml-64' : '' }}">
            @guest
            <!-- Top Bar for Guests -->
            <nav class="bg-white border-b border-gray-100 sticky top-0 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('habilidades.index') }}" class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                Runa Maki
                            </a>
                            <a href="{{ route('habilidades.index') }}" class="ml-8 text-sm text-gray-700 hover:text-gray-900">Habilidades</a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Registrarse</a>
                        </div>
                    </div>
                </div>
            </nav>
            @endguest

            @auth
            <!-- Motivational Banner Component -->
            <x-motivational-banner />

            <!-- Top utility bar: compact search + quick stats -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @auth
                    @php
                        $habilidadesCount = Auth::user()->habilidades()->count();
                        $pendientesCount = Auth::user()->truequesOfrecidos()->where('estado','pendiente')->count() + Auth::user()->truequesRecibidos()->where('estado','pendiente')->count();
                        $mensajesNoLeidos = Auth::user()->mensajes()->where('leido', false)->count();
                    @endphp
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-4">
                            <!-- Mobile menu button (visible only on mobile) -->
                            <button id="sidebar-toggle" class="lg:hidden p-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <form action="{{ route('habilidades.buscar') }}" method="GET" class="hidden md:flex items-center gap-2">
                                <input name="q" type="text" placeholder="Buscar habilidades..." value="{{ request('q') }}" class="h-9 rounded-md border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm px-3 text-sm w-64 focus:ring-0 focus:border-indigo-300 dark:focus:border-indigo-500" />
                                <button type="submit" class="h-9 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm transition">Buscar</button>
                            </form>

                            <div class="hidden sm:flex items-center gap-2">
                                <div class="text-xs text-gray-600 dark:text-gray-400 px-3 py-1 bg-gray-50 dark:bg-gray-700 rounded-md">Habilidades <span class="font-semibold text-gray-800 dark:text-white ml-2">{{ $habilidadesCount }}</span></div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 px-3 py-1 bg-gray-50 dark:bg-gray-700 rounded-md">Pendientes <span class="font-semibold text-gray-800 dark:text-white ml-2">{{ $pendientesCount }}</span></div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 px-3 py-1 bg-gray-50 dark:bg-gray-700 rounded-md">Mensajes <span class="font-semibold text-gray-800 dark:text-white ml-2">{{ $mensajesNoLeidos }}</span></div>
                            </div>
                        </div>

                        <!-- Centered brand/title -->
                        <div class="hidden md:flex flex-1 justify-center">
                            <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-800 dark:text-white">Runa Maki</a>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('trueques.index', ['estado' => 'pendiente']) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ver pendientes</a>
                            <a href="{{ route('trueques.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Mensajes</a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
            @endauth

            <!-- Page Content -->
            <main class="min-h-screen">
                <div class="py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="alert-success mb-6" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert-error mb-6" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                if (sidebar && sidebarOverlay) {
                    // Toggle sidebar visibility
                    const isHidden = sidebar.classList.contains('-translate-x-full');
                    
                    if (isHidden) {
                        // Show sidebar
                        sidebar.classList.remove('-translate-x-full');
                        sidebarOverlay.classList.remove('hidden');
                    } else {
                        // Hide sidebar
                        sidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                    }
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    // Close sidebar when clicking overlay
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }

            // Close sidebar on window resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024 && sidebar && sidebarOverlay) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                }
            });

            // Close sidebar when clicking on navigation links on mobile
            if (window.innerWidth < 1024) {
                const navLinks = sidebar.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                    });
                });
            }

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const darkModeIconSun = document.getElementById('dark-mode-icon-sun');
            const darkModeIconMoon = document.getElementById('dark-mode-icon-moon');
            const darkModeText = document.getElementById('dark-mode-text');
            
            // Update UI based on current theme (already set by initial script)
            function updateDarkModeUI() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    darkModeIconSun.classList.remove('hidden');
                    darkModeIconMoon.classList.add('hidden');
                    darkModeText.textContent = 'Modo Claro';
                } else {
                    darkModeIconSun.classList.add('hidden');
                    darkModeIconMoon.classList.remove('hidden');
                    darkModeText.textContent = 'Modo Oscuro';
                }
            }
            
            // Initialize UI
            updateDarkModeUI();
            
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    
                    if (isDark) {
                        // Switch to light mode
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        // Switch to dark mode
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    
                    // Update UI
                    updateDarkModeUI();
                });
            }
        });
    </script>
    @endauth
</body>
</html>