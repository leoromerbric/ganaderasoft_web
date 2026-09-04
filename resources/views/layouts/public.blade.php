@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 text-gray-800 flex flex-col justify-between selection:bg-ganaderasoft-celeste selection:text-white relative overflow-x-hidden">
    
    @php
        $isAuthenticated = (bool) session('authenticated', false);
    @endphp

    <!-- Encabezado / Navbar Contextual -->
    @hasSection('header')
        @yield('header')
    @else
        @if($isAuthenticated)
            {{-- Navbar oficial del sistema para usuarios autenticados --}}
            <div class="relative z-30">
                <x-layouts.navbar />
            </div>
        @else
            {{-- Header público para visitantes / invitados --}}
            <x-layouts.public-header>
                @yield('header-links')
            </x-layouts.public-header>
        @endif
    @endif

    <!-- Contenido Principal -->
    <main class="flex-grow flex flex-col relative overflow-hidden">
        @yield('main-content')
    </main>

    <!-- Pie de Página Modular -->
    <x-layouts.public-footer />

</div>
@endsection
