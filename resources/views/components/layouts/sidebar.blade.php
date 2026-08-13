<aside id="sidebar" class="w-64 bg-white shadow-md min-h-screen">
    <!-- Toggle Button -->
    <div class="p-4 border-b border-gray-200">
        <button id="sidebar-toggle" class="flex items-center justify-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
    
    <nav class="mt-6">
        <div class="px-4 mb-6">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Menú Principal</h3>
        </div>
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🏠</span>
            <span class="menu-text font-medium">Dashboard Principal</span>
        </a>

        <!-- Gestión de Fincas -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión de Fincas</h3>
        </div>
        <a href="{{ route('fincas.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('fincas.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🏡</span>
            <span class="menu-text font-medium">Lista de Fincas</span>
        </a>

        <!-- Gestión de Animales -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión de Animales</h3>
        </div>
        <a href="{{ route('rebanos.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('rebanos.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🐄</span>
            <span class="menu-text font-medium">Rebaños</span>
        </a>
        <a href="{{ route('animales.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('animales.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">📋</span>
            <span class="menu-text font-medium">Lista de Animales</span>
        </a>
        <a href="{{ route('cambios-animal.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('cambios-animal.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">📝</span>
            <span class="menu-text font-medium">Cambios de Animal</span>
        </a>
        <a href="{{ route('movimiento-rebano.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('movimiento-rebano.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🚛</span>
            <span class="menu-text font-medium">Movimiento de Rebaño</span>
        </a>

        <!-- Personal -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal</h3>
        </div>
        <a href="{{ route('personal-finca.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('personal-finca.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">👥</span>
            <span class="menu-text font-medium">Personal de Finca</span>
        </a>

        <!-- Módulo Reproductivo -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo Reproductivo</h3>
        </div>
        <a href="{{ route('registro-celo.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('registro-celo.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🌡️</span>
            <span class="menu-text font-medium">Registro de Celo</span>
        </a>
        <a href="{{ route('servicio-animal.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('servicio-animal.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🐂</span>
            <span class="menu-text font-medium">Servicio Animal</span>
        </a>
        <a href="{{ route('reproduccion-animal.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('reproduccion-animal.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🍼</span>
            <span class="menu-text font-medium">Reproducción</span>
        </a>
        <a href="{{ route('palpacion.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('palpacion.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🔬</span>
            <span class="menu-text font-medium">Palpación</span>
        </a>
        <a href="{{ route('semen-toro.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('semen-toro.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🧬</span>
            <span class="menu-text font-medium">Semen de Toro</span>
        </a>

        <!-- Producción Lechera -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción Lechera</h3>
        </div>
        <a href="{{ route('lactancia.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('lactancia.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🐄</span>
            <span class="menu-text font-medium">Períodos de Lactancia</span>
        </a>
        <a href="{{ route('leche.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('leche.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🥛</span>
            <span class="menu-text font-medium">Registro de Leche</span>
        </a>

        <!-- Peso y Medidas -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Peso y Medidas</h3>
        </div>
        <a href="{{ route('peso-corporal.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('peso-corporal.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">📊</span>
            <span class="menu-text font-medium">Peso Corporal</span>
        </a>
        <a href="{{ route('medidas-corporales.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('medidas-corporales.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">📏</span>
            <span class="menu-text font-medium">Medidas Corporales</span>
        </a>

        <!-- Módulo Sanitario -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo Sanitario</h3>
        </div>
        <a href="{{ route('diagnostico.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('diagnostico.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🩺</span>
            <span class="menu-text font-medium">Diagnósticos</span>
        </a>
        <a href="{{ route('tratamiento.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('tratamiento.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">💊</span>
            <span class="menu-text font-medium">Tratamientos</span>
        </a>
        <a href="{{ route('vacuna.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('vacuna.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">💉</span>
            <span class="menu-text font-medium">Vacunas</span>
        </a>
        <a href="{{ route('vacunacion.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('vacunacion.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">💉</span>
            <span class="menu-text font-medium">Vacunación</span>
        </a>
        <a href="{{ route('casa-comercial.index') }}" class="menu-item flex items-center px-6 py-3 text-gray-700 hover:bg-ganaderasoft-celeste hover:text-white transition-colors duration-200 {{ request()->routeIs('casa-comercial.*') ? 'bg-ganaderasoft-azul text-white border-l-4 border-ganaderasoft-verde' : '' }}">
            <span class="menu-icon text-xl mr-3">🏭</span>
            <span class="menu-text font-medium">Casas Comerciales</span>
        </a>

        <!-- Reportes -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Reportes</h3>
        </div>
        <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
            <span class="menu-icon text-xl mr-3">📊</span>
            <span class="menu-text font-medium">Reportes Productivos</span>
            <span class="menu-text ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Próximamente</span>
        </a>

        <!-- Cerrar Sesión -->
        <div class="mt-6 px-4 mb-2">
            <h3 class="menu-title text-xs font-semibold text-gray-500 uppercase tracking-wider">Cuenta</h3>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item w-full flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                <span class="menu-icon text-xl mr-3">🚪</span>
                <span class="menu-text font-medium">Cerrar Sesión</span>
            </button>
        </form>
    </nav>
</aside>
