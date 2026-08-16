<style>
    @media (min-width: 1024px) {
        #sidebar {
            position: sticky !important;
            top: 4rem !important;
            align-self: flex-start !important;
            min-height: calc(100vh - 4rem) !important;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
    }

    /* Ampliar el ancho del sidebar desplegado para dar espacio holgado a todos los textos */
    #sidebar:not(.w-20):not(.collapsed) {
        width: 17.5rem !important;
    }

    /* Ancho colapsado en desktop (w-20 o collapsed) */
    #sidebar.w-20,
    #sidebar.collapsed {
        width: 5rem !important;
    }

    /* Ocultar submenús y textos en estado colapsado (w-20 o collapsed) */
    #sidebar.w-20 .menu-sublist,
    #sidebar.w-20 .menu-title,
    #sidebar.w-20 .menu-text,
    #sidebar.collapsed .menu-sublist,
    #sidebar.collapsed .menu-title,
    #sidebar.collapsed .menu-text {
        display: none !important;
    }

    /* Asegurar que el texto permanezca siempre en 1 sola línea sin partirse */
    .menu-text {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    @media (max-width: 1023px) {
        #sidebar {
            position: fixed !important;
            top: 4rem !important;
            left: 0 !important;
            height: calc(100vh - 4rem) !important;
            z-index: 50 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff !important;
            overflow-y: auto !important;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* En dispositivos pequeños al colapsar (w-20 o collapsed) */
        #sidebar.w-20,
        #sidebar.collapsed {
            width: 0 !important;
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
            overflow: visible !important;
        }

        #sidebar.w-20 nav,
        #sidebar.w-20 .menu-title,
        #sidebar.collapsed nav,
        #sidebar.collapsed .menu-title {
            display: none !important;
        }

        /* Pestaña fija adosada directamente al borde izquierdo de la pantalla */
        #sidebar.w-20 #sidebar-toggle-wrapper,
        #sidebar.collapsed #sidebar-toggle-wrapper {
            position: fixed !important;
            left: 0 !important;
            top: 4.75rem !important;
            z-index: 60 !important;
            display: block !important;
        }

        #sidebar.w-20 #sidebar-toggle,
        #sidebar.collapsed #sidebar-toggle {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            box-shadow: 4px 4px 15px -2px rgba(0, 0, 0, 0.12), 2px 2px 6px -1px rgba(0, 0, 0, 0.06) !important;
            border-top-right-radius: 0.85rem !important;
            border-bottom-right-radius: 0.85rem !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border: 1px solid #e5e7eb !important;
            border-left: 2px solid #2563eb !important;
            padding: 0.6rem 0.75rem !important;
            color: #1e40af !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
        }

        #sidebar.w-20 #sidebar-toggle:hover,
        #sidebar.collapsed #sidebar-toggle:hover {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            transform: translateX(2px) !important;
        }
    }
</style>

<aside id="sidebar" class="w-64 bg-white shadow-sm border-r border-gray-100 transition-[width] duration-300">
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.currentScript.parentElement.classList.add('collapsed');
        }
    </script>
    <!-- Header / Toggle Button -->
    <div class="px-4 h-16 border-b border-gray-100 flex items-center justify-between">
        <span class="menu-title text-xs font-bold uppercase tracking-wider text-gray-400 leading-none">Navegación</span>
        <div id="sidebar-toggle-wrapper" class="flex items-center justify-center">
            <button id="sidebar-toggle" type="button" class="p-2 text-gray-500 hover:text-ganaderasoft-azul hover:bg-ganaderasoft-celeste/10 rounded-xl transition-all duration-200 focus:outline-none flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
    
    @php
        $authUser = session('user');
        $userStatus = strtolower($authUser['status'] ?? 'active');
        $isUserSuspended = in_array($userStatus, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
    @endphp

    <nav class="p-3 space-y-1.5">
        @if($isUserSuspended)
            <!-- Enlace exclusivo de Perfil para usuarios suspendidos -->
            <a href="{{ route('profile') }}" class="menu-item flex items-center px-3 py-2.5 rounded-xl text-base font-semibold transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-ganaderasoft-azul text-white shadow-sm' : 'text-gray-700 hover:bg-ganaderasoft-celeste/10 hover:text-ganaderasoft-azul' }}">
                <span class="menu-icon text-xl mr-3">👤</span>
                <span class="menu-text">Mi perfil</span>
            </a>
        @else
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="menu-item flex items-center px-3 py-2.5 rounded-xl text-base font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-ganaderasoft-azul text-white shadow-sm' : 'text-gray-700 hover:bg-ganaderasoft-celeste/10 hover:text-ganaderasoft-azul' }}">
                <span class="menu-icon text-xl mr-3">🏠</span>
                <span class="menu-text">Dashboard principal</span>
            </a>

        <!-- Fincas y personal -->
        @php
            $isFincasActive = request()->routeIs('fincas.*', 'personal-finca.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-fincas')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">🏡</span>
                    <span class="menu-text">Fincas y personal</span>
                </div>
                <svg id="sub-fincas-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isFincasActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-fincas" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isFincasActive ? '' : 'hidden' }}">
                <a href="{{ route('fincas.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('fincas.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Lista de fincas</span>
                </a>
                <a href="{{ route('personal-finca.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('personal-finca.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Personal de finca</span>
                </a>
            </div>
        </div>

        <!-- Gestión de ganado -->
        @php
            $isGanadoActive = request()->routeIs('rebanos.*', 'animales.*', 'cambios-animal.*', 'movimiento-rebano.*', 'peso-corporal.*', 'medidas-corporales.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-ganado')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">🐄</span>
                    <span class="menu-text">Gestión de ganado</span>
                </div>
                <svg id="sub-ganado-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isGanadoActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-ganado" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isGanadoActive ? '' : 'hidden' }}">
                <a href="{{ route('rebanos.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('rebanos.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Rebaños</span>
                </a>
                <a href="{{ route('animales.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('animales.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Lista de animales</span>
                </a>
                <a href="{{ route('cambios-animal.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('cambios-animal.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Cambios de animal</span>
                </a>
                <a href="{{ route('movimiento-rebano.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('movimiento-rebano.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Movimientos de rebaño</span>
                </a>
                <a href="{{ route('peso-corporal.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('peso-corporal.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Peso corporal</span>
                </a>
                <a href="{{ route('medidas-corporales.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('medidas-corporales.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Medidas corporales</span>
                </a>
            </div>
        </div>

        <!-- Módulo reproductivo -->
        @php
            $isReproActive = request()->routeIs('registro-celo.*', 'servicio-animal.*', 'reproduccion-animal.*', 'palpacion.*', 'semen-toro.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-repro')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">🍼</span>
                    <span class="menu-text">Módulo reproductivo</span>
                </div>
                <svg id="sub-repro-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isReproActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-repro" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isReproActive ? '' : 'hidden' }}">
                <a href="{{ route('registro-celo.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('registro-celo.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Registro de celo</span>
                </a>
                <a href="{{ route('servicio-animal.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('servicio-animal.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Servicio animal</span>
                </a>
                <a href="{{ route('reproduccion-animal.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('reproduccion-animal.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Reproducción</span>
                </a>
                <a href="{{ route('palpacion.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('palpacion.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Palpación</span>
                </a>
                <a href="{{ route('semen-toro.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('semen-toro.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Semen de toro</span>
                </a>
            </div>
        </div>

        <!-- Producción lechera -->
        @php
            $isLecheActive = request()->routeIs('lactancia.*', 'leche.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-leche')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">🥛</span>
                    <span class="menu-text">Producción lechera</span>
                </div>
                <svg id="sub-leche-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isLecheActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-leche" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isLecheActive ? '' : 'hidden' }}">
                <a href="{{ route('lactancia.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('lactancia.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Períodos de lactancia</span>
                </a>
                <a href="{{ route('leche.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('leche.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Registro de leche</span>
                </a>
            </div>
        </div>

        <!-- Módulo sanitario -->
        @php
            $isSaludActive = request()->routeIs('diagnostico.*', 'tratamiento.*', 'vacuna.*', 'vacunacion.*', 'casa-comercial.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-salud')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">🩺</span>
                    <span class="menu-text">Módulo sanitario</span>
                </div>
                <svg id="sub-salud-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isSaludActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-salud" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isSaludActive ? '' : 'hidden' }}">
                <a href="{{ route('diagnostico.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('diagnostico.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Diagnósticos</span>
                </a>
                <a href="{{ route('tratamiento.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('tratamiento.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Tratamientos</span>
                </a>
                <a href="{{ route('vacuna.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('vacuna.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Vacunas</span>
                </a>
                <a href="{{ route('vacunacion.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('vacunacion.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Vacunación</span>
                </a>
                <a href="{{ route('casa-comercial.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('casa-comercial.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Casas comerciales</span>
                </a>
            </div>
        </div>

        <!-- Módulo de reportes -->
        @php
            $isReportesActive = request()->routeIs('reportes.*');
        @endphp
        <div>
            <button type="button" onclick="toggleSubmenu('sub-reportes')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center min-w-0 pr-1">
                    <span class="menu-icon text-xl mr-3 shrink-0">📊</span>
                    <span class="menu-text">Módulo de reportes</span>
                </div>
                <svg id="sub-reportes-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isReportesActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="sub-reportes" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isReportesActive ? '' : 'hidden' }}">
                <a href="{{ route('reportes.general') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('reportes.general') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Reporte general</span>
                </a>
                <a href="{{ route('reportes.reproductivo') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('reportes.reproductivo') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Reporte reproductivo</span>
                </a>
                <a href="{{ route('reportes.pesaje-leche') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('reportes.pesaje-leche') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span class="menu-text">Reporte de pesaje de leche</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Perfil y Cerrar sesión -->
        <div class="pt-4 border-t border-gray-100 mt-4 space-y-1">
            @if(!$isUserSuspended)
            <!-- Botón Mi perfil (visible en dispositivos móviles para usuarios activos) -->
            <a href="{{ route('profile') }}" class="sm:hidden menu-item w-full flex items-center px-3 py-2.5 text-base font-semibold {{ request()->routeIs('profile') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-xl transition-colors duration-200">
                <span class="menu-icon text-xl mr-3 shrink-0">👤</span>
                <span class="menu-text">Mi perfil</span>
            </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item w-full flex items-center px-3 py-2.5 text-base font-semibold text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors duration-200">
                    <span class="menu-icon text-xl mr-3 shrink-0">🚪</span>
                    <span class="menu-text">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<script>
    /**
     * Alterna la visibilidad de un submenú del sidebar y la rotación de su flecha indicadora.
     * Si el sidebar está en estado colapsado (reducido/cerrado), primero lo despliega automáticamente
     * antes de mostrar el contenido del submenú.
     * 
     * @param {string} id - El ID HTML del contenedor del submenú (ej: 'sub-fincas', 'sub-ganado').
     */
    function toggleSubmenu(id) {
        const sidebar = document.getElementById('sidebar');
        // Evalúa si el sidebar está colapsado comprobando si tiene la clase 'w-20' o 'collapsed'
        const isCollapsed = sidebar && (sidebar.classList.contains('w-20') || sidebar.classList.contains('collapsed'));

        // 1. Si está colapsado, simula un clic en el botón toggle para abrir el sidebar automáticamente
        if (isCollapsed) {
            const toggleBtn = document.getElementById('sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.click();
            }
        }

        // 2. Controla la visibilidad del contenedor de listas del submenú
        const el = document.getElementById(id);
        const arrow = document.getElementById(id + '-arrow');
        if (el) {
            if (isCollapsed) {
                // Al abrir el sidebar desde estado cerrado, asegura que el submenú quede visible
                el.classList.remove('hidden');
            } else {
                // Alterna entre ocultar y mostrar la lista de opciones
                el.classList.toggle('hidden');
            }
        }

        // 3. Anima la rotación de la flecha indicadora
        if (arrow) {
            if (isCollapsed) {
                // Apunta la flecha hacia arriba al expandirse
                arrow.classList.add('rotate-180');
            } else {
                // Alterna la dirección de la flecha según el estado
                arrow.classList.toggle('rotate-180');
            }
        }
    }
</script>
