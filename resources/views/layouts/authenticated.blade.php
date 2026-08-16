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
        <x-layouts.sidebar />

        <!-- Main Content -->
        <main id="main-content" class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
