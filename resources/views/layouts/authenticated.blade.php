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
    <!-- Header/Navbar Component -->
    <x-layouts.navbar />

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <!-- Sidebar Navigation Component -->
        @php
            if (request()->is('admin*')) {
                session(['active_sidebar' => 'global_admin']);
            } elseif (!request()->is('perfil')) {
                session(['active_sidebar' => 'operative']);
            }
            $activeSidebar = session('active_sidebar', 'operative');
        @endphp

        @if($activeSidebar === 'global_admin')
            @hasrole(['global_admin', 'admin'])
                <x-layouts.sidebar-admin />
            @else
                <x-layouts.sidebar />
            @endhasrole
        @else
            <x-layouts.sidebar />
        @endif

        <!-- Main Content -->
        <main id="main-content" class="flex-1 p-4 sm:p-8 min-w-0">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
