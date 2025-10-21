@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background wave decoration -->
    <div class="absolute bottom-0 left-0 right-0 h-64 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul opacity-10">
        <svg viewBox="0 0 1200 200" class="w-full h-full">
            <path d="M0,100 Q300,50 600,100 T1200,100 L1200,200 L0,200 Z" fill="currentColor"/>
        </svg>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo and Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="bg-white p-4 rounded-2xl shadow-xl">
                    <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-16 h-16 object-contain">
                </div>
            </div>
            <h2 class="text-4xl font-bold text-ganaderasoft-negro mb-2">GanaderaSoft</h2>
            <p class="text-lg text-gray-600">Sistema de Gestión de Ganadería</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
            <div>
                <h3 class="text-2xl font-semibold text-ganaderasoft-negro text-center mb-2">Iniciar Sesión</h3>
                <p class="text-sm text-gray-500 text-center">Ingrese sus credenciales para continuar</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email') }}"
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition"
                        placeholder="admin@demo.cl"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ganaderasoft-celeste transition-all duration-200 transform hover:scale-105"
                    >
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <!-- Android App Download Link -->
            <div class="mt-6 p-4 bg-ganaderasoft-celeste/10 rounded-lg border border-ganaderasoft-celeste/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-ganaderasoft-azul" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.523 7.39c-.002-.119-.026-.236-.065-.349-.04-.113-.1-.218-.177-.31l-1.48-1.48c-.092-.077-.197-.137-.31-.177-.113-.039-.23-.063-.349-.065H8.25c-.414 0-.75.336-.75.75v10.5c0 .414.336.75.75.75h7.5c.414 0 .75-.336.75-.75V7.39zM12 15.75c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zm3.75-6.75h-7.5v-1.5h7.5v1.5z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-ganaderasoft-negro">Aplicación Android</p>
                            <p class="text-xs text-gray-600">Descarga la app móvil</p>
                        </div>
                    </div>
                    <a href="https://drive.google.com/file/d/1iPavcba9L-VDhQbt8Du3yN9B39EkBYj1/view?usp=drive_link" 
                       target="_blank"
                       class="px-4 py-2 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white text-sm font-medium rounded-lg hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 transform hover:scale-105">
                        Descargar APK
                    </a>
                </div>
            </div>
        </div>

        <!-- Participating Organizations Logos -->
        <div class="flex justify-center my-6">
            <img src="{{ asset('images/logos_participantes.png') }}" alt="Logos Participantes" class="max-w-full h-auto rounded-lg shadow-md">
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500">
            &copy; 2025 GanaderaSoft. Todos los derechos reservados.
        </p>
    </div>
</div>
@endsection
