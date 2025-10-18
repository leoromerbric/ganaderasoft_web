<!DOCTYPE html>
<html lang="es-VE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestión de Finca') - GanaderaSoft</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header/Navbar -->
    <nav class="bg-white shadow-md border-b-4 border-ganaderasoft-celeste">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Title -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-ganaderasoft-negro">GanaderaSoft</h1>
                        <p class="text-xs text-gray-500">Sistema de Gestión</p>
                    </div>
                </a>

                <!-- Breadcrumb / Current Finca -->
                @if(session('selected_finca'))
                <div class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('fincas.index') }}" class="text-ganaderasoft-celeste hover:text-blue-600 font-medium">
                        Lista de Fincas
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-700 font-semibold">{{ session('selected_finca')['Nombre'] }}</span>
                </div>
                @endif

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

    <!-- Main Content (no sidebar) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
</body>
</html>
