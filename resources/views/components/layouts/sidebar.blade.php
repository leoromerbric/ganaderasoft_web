<x-layouts.sidebar-base>
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
        $isGanadoActive = request()->routeIs('rebanos.*', 'animales.*', 'cambios-animal.*', 'movimiento-rebano.*', 'peso-corporal.*', 'medidas-corporales.*', 'razas.*');
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
            <a href="{{ route('razas.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('razas.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <span class="menu-text">Composición de raza</span>
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
        $isSaludActive = request()->routeIs('diagnostico.*', 'tratamiento.*', 'vacunacion.*');
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

            <a href="{{ route('vacunacion.index') }}" class="block px-3 py-2 rounded-lg text-[15px] font-medium {{ request()->routeIs('vacunacion.*') ? 'bg-ganaderasoft-azul text-white font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <span class="menu-text">Vacunación</span>
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
</x-layouts.sidebar-base>
