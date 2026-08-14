<nav class="bg-white shadow-md border-b-4 border-ganaderasoft-celeste sticky top-0 z-30">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
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

            <!-- Active Finca Context Badge (if set in session) -->
            @if(session('selected_finca'))
                @php
                    $activeFinca = session('selected_finca');
                    $activeFincaName = $activeFinca['nombre'] ?? $activeFinca['Nombre'] ?? 'Finca';
                    $activeFincaId = $activeFinca['id'] ?? null;
                @endphp
                <div class="hidden sm:flex items-center space-x-2 px-3.5 py-1.5 bg-ganaderasoft-celeste/10 border border-ganaderasoft-celeste/30 rounded-full text-xs">
                    <span class="text-sm">🏡</span>
                    <a href="{{ route('fincas.dashboard', $activeFincaId) }}" class="font-bold text-ganaderasoft-azul hover:text-ganaderasoft-celeste transition-colors">
                        Finca: {{ $activeFincaName }}
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('fincas.index') }}" class="text-gray-500 hover:text-ganaderasoft-azul font-medium transition-colors">
                        Cambiar
                    </a>
                </div>
            @endif

            <!-- User Info and Logout -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('profile') }}" class="text-right hidden sm:block hover:opacity-80 transition-opacity">
                    <p class="text-sm font-semibold text-ganaderasoft-negro">{{ session('user')['name'] ?? 'Usuario' }}</p>
                    <p class="text-xs text-gray-500">{{ !empty(session('user')['roles']) ? implode(', ', array_map('ucfirst', session('user')['roles'])) : 'Usuario' }}</p>
                </a>
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
