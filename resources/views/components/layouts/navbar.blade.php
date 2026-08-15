<nav class="bg-white shadow-md border-b-4 border-ganaderasoft-celeste sticky top-0 z-30">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Brand Logo & Status -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="bg-white p-1.5 rounded-xl shadow-xs border border-gray-100 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-ganaderasoft-negro group-hover:text-ganaderasoft-azul transition-colors">GanaderaSoft</h1>
                    <p class="text-xs font-medium text-gray-500">Sistema de Gestión</p>
                </div>
            </a>

            <!-- User Info & Profile Link -->
            <div class="flex items-center">
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 transition-all duration-200 hover:opacity-85 group" title="Ver mi perfil">
                    <div class="relative flex-shrink-0">
                        <div class="w-9 h-9 rounded-full bg-ganaderasoft-azul text-white font-bold flex items-center justify-center text-sm shadow-xs border border-ganaderasoft-azul/20">
                            {{ strtoupper(substr(session('user.name') ?? 'U', 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden sm:flex flex-col justify-center text-left">
                        <span class="text-sm font-bold text-ganaderasoft-negro leading-tight group-hover:text-ganaderasoft-azul transition-colors">
                            {{ session('user.name') ?? 'Usuario' }}
                        </span>
                        <span class="text-xs font-medium text-gray-500 leading-tight mt-0.5">
                            {{ session('user.type_user') ?? (!empty(session('user.roles')) ? ucfirst(session('user.roles')[0]) : 'Usuario') }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-ganaderasoft-azul group-hover:translate-x-0.5 transition-all hidden sm:block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>
