<x-layouts.sidebar-base>
    <div class="mt-2 mb-1 px-3">
        <span class="menu-title text-[10px] font-bold uppercase tracking-wider text-gray-400">Administración</span>
    </div>

    <!-- Panel principal de Administración -->
    <a href="{{ route('admin.dashboard') }}" class="menu-item flex items-center px-3 py-2.5 rounded-xl text-base font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-ganaderasoft-azul text-white shadow-sm' : 'text-gray-700 hover:bg-ganaderasoft-celeste/10 hover:text-ganaderasoft-azul' }}">
        <span class="menu-icon text-xl mr-3">👑</span>
        <span class="menu-text">Panel principal</span>
    </a>



    <a href="{{ route('admin.users.index') }}" class="menu-item flex items-center px-3 py-2.5 rounded-xl text-base font-semibold transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-ganaderasoft-azul text-white shadow-sm' : 'text-gray-700 hover:bg-ganaderasoft-celeste/10 hover:text-ganaderasoft-azul' }}">
        <span class="menu-icon text-xl mr-3">👥</span>
        <span class="menu-text">Usuarios</span>
    </a>

    @php
        $isAdminParamActive = request()->routeIs('admin.tipos-trabajador.*', 'admin.tipos-animal.*', 'admin.etapas.*', 'admin.estados-salud.*', 'admin.dias-palpacion.*', 'admin.foliculos.*', 'admin.vacunas.*', 'admin.casas-comerciales.*', 'admin.razas.*');
    @endphp
    <div>
        <button type="button" onclick="toggleSubmenu('sub-admin-param')" class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
            <div class="flex items-center min-w-0 pr-1">
                <span class="menu-icon text-xl mr-3 shrink-0">⚙️</span>
                <span class="menu-text">Catálogos maestros</span>
            </div>
            <svg id="sub-admin-param-arrow" class="w-4 h-4 text-gray-400 shrink-0 ml-2.5 transition-transform duration-200 menu-text {{ $isAdminParamActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div id="sub-admin-param" class="ml-4 pl-3 border-l-2 border-gray-100 my-1 space-y-1 menu-sublist {{ $isAdminParamActive ? '' : 'hidden' }}">
            <a href="{{ route('admin.tipos-trabajador.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.tipos-trabajador.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Tipos de trabajador</span>
            </a>
            <a href="{{ route('admin.tipos-animal.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.tipos-animal.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Tipos de animal</span>
            </a>
            <a href="{{ route('admin.razas.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.razas.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Composición de raza</span>
            </a>

            <a href="{{ route('admin.etapas.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.etapas.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Etapas de vida</span>
            </a>
            <a href="{{ route('admin.estados-salud.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.estados-salud.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Estados de salud</span>
            </a>
            <a href="{{ route('admin.dias-palpacion.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.dias-palpacion.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Días de palpación</span>
            </a>
            <a href="{{ route('admin.foliculos.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.foliculos.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Folículos</span>
            </a>
            <a href="{{ route('admin.vacunas.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.vacunas.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Vacunas</span>
            </a>
            <a href="{{ route('admin.casas-comerciales.index') }}" class="block px-3 py-2 rounded-lg text-[14px] font-medium {{ request()->routeIs('admin.casas-comerciales.*') ? 'bg-ganaderasoft-azul text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="menu-text">Casas comerciales</span>
            </a>
        </div>
    </div>
</x-layouts.sidebar-base>
