<!DOCTYPE html>
<html lang="es-VE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - GanaderaSoft</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header/Navbar -->
    <nav class="bg-white shadow-md border-b-4 border-ganaderasoft-celeste">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Title -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-ganaderasoft-negro">GanaderaSoft</h1>
                        <p class="text-xs text-gray-500">Sistema de Gestión</p>
                    </div>
                </div>

                <!-- User Info and Logout -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-ganaderasoft-negro">{{ session('user')['name'] ?? 'Usuario' }}</p>
                        <p class="text-xs text-gray-500">{{ session('user')['type_user'] ?? 'Propietario' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white shadow-md min-h-screen">
            <nav class="mt-6">
                <div class="px-4 mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menú Principal</h3>
                </div>
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-ganaderasoft-celeste text-white' : '' }}">
                    <span class="text-xl mr-3">🏠</span>
                    <span class="font-medium">Dashboard Principal</span>
                </a>

                <!-- Gestión de Fincas -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión de Fincas</h3>
                </div>
                <a href="{{ route('fincas.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('fincas.*') ? 'bg-ganaderasoft-celeste text-white' : '' }}">
                    <span class="text-xl mr-3">🏡</span>
                    <span class="font-medium">Lista de Fincas</span>
                </a>

                <!-- Gestión de Animales -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión de Animales</h3>
                </div>
                <a href="{{ route('rebanos.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('rebanos.*') ? 'bg-ganaderasoft-celeste text-white' : '' }}">
                    <span class="text-xl mr-3">🐄</span>
                    <span class="font-medium">Rebaños</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                    <span class="text-xl mr-3">📋</span>
                    <span class="font-medium">Lista de Animales</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
                </a>

                <!-- Personal -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal</h3>
                </div>
                <a href="{{ route('personal.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('personal.*') ? 'bg-ganaderasoft-celeste text-white' : '' }}">
                    <span class="text-xl mr-3">👥</span>
                    <span class="font-medium">Personal de Finca</span>
                </a>

                <!-- Módulo Reproductivo -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo Reproductivo</h3>
                </div>
                <a href="#" class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                    <span class="text-xl mr-3">💝</span>
                    <span class="font-medium">Calendario</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
                </a>

                <!-- Producción Lechera -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción Lechera</h3>
                </div>
                <a href="#" class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                    <span class="text-xl mr-3">🥛</span>
                    <span class="font-medium">Registro Diario</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
                </a>

                <!-- Módulo Sanitario -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo Sanitario</h3>
                </div>
                <a href="#" class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                    <span class="text-xl mr-3">🏥</span>
                    <span class="font-medium">Plan de Vacunación</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
                </a>

                <!-- Reportes -->
                <div class="mt-6 px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reportes</h3>
                </div>
                <a href="#" class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                    <span class="text-xl mr-3">📊</span>
                    <span class="font-medium">Reportes Productivos</span>
                    <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
