@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12 bg-white relative">
    
    <!-- Left Column: Brand & Institutions (Left-Aligned Panel) -->
    <div class="lg:col-span-5 bg-[#f2f8fc] border-b lg:border-b-0 lg:border-r border-gray-100 p-8 sm:p-12 lg:p-14 flex flex-col justify-between items-start text-left min-h-[420px] lg:min-h-screen">
        
        <!-- Brand Info Left-Aligned -->
        <div class="my-auto space-y-6 max-w-md py-6">
            <a href="{{ url('/') }}" class="inline-block bg-white p-3.5 rounded-2xl shadow-md border border-gray-100/80 transform hover:scale-105 transition-transform duration-200">
                <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-14 h-14 object-contain">
            </a>

            <div>
                <h2 class="text-3xl sm:text-4xl font-black text-ganaderasoft-negro tracking-tight mb-1">
                    Ganadera<span class="text-ganaderasoft-azul">Soft</span>
                </h2>
                <p class="text-xs font-bold uppercase tracking-wider text-ganaderasoft-azul mb-3">Facultad de Agronomía</p>
                <p class="text-sm text-gray-600 leading-relaxed max-w-sm">
                    Sistema para el control y gestión de la producción bovina, registros de leche, genealogía y rebaños.
                </p>
            </div>
        </div>

        <!-- Participating Institutions Left-Aligned at Bottom -->
        <div class="w-full pt-8 border-t border-gray-200/60 max-w-md">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Instituciones participantes</p>
            <img src="{{ asset('images/logos_participantes.png') }}" alt="Logos Participantes" class="max-w-full h-auto max-h-16 object-contain">
        </div>
    </div>

    <!-- Right Column: Login Form -->
    <div class="lg:col-span-7 bg-white p-8 sm:p-12 lg:p-16 flex flex-col justify-between min-h-screen">
        
        <div class="max-w-md w-full mx-auto my-auto space-y-8 py-4">
            
            <!-- Top Back Link -->
            <div>
                <a href="{{ url('/') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-ganaderasoft-azul transition-colors mb-6">
                    ← Volver al inicio
                </a>

                <h3 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Iniciar sesión</h3>
                <p class="text-sm text-gray-500">Ingrese sus credenciales para acceder al sistema</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-xs font-semibold" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs font-semibold" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Body -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                        Correo electrónico
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email') }}"
                        required 
                        class="appearance-none block w-full px-4 py-3.5 border border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent text-sm transition shadow-sm"
                        placeholder="admin@demo.cl"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                        Contraseña
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="appearance-none block w-full px-4 py-3.5 border border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent text-sm transition shadow-sm"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-ganaderasoft-azul/20 text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#007B92] to-ganaderasoft-azul hover:opacity-95 transition-all duration-200 transform hover:scale-[1.01]"
                >
                    Iniciar sesión
                </button>
            </form>

            <!-- APK Link & Footer -->
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <a href="https://drive.google.com/file/d/19g-CpAm9VyXjgKSWMgS8L8zHcl66gvdg/view?usp=drive_link" 
                   target="_blank"
                   class="text-ganaderasoft-azul font-semibold hover:underline">
                    Descargar app Android (APK)
                </a>
                <span class="text-gray-400 text-[11px]">&copy; {{ date('Y') }} GanaderaSoft</span>
            </div>
        </div>

    </div>

</div>
@endsection
